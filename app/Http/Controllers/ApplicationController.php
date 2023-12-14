<?php

	namespace App\Http\Controllers;

	use App\Http\Requests\FormationTypeRequest;
	use App\Http\Requests\PartnerRequest;
	use App\Http\Requests\SaveStudentRequest;
	use App\Models\FormationSubCategory;
	use App\Models\FormationType;
	use App\Models\Partner;
use App\Models\Session;
use App\Models\Student;
	use App\Models\User;
	use Illuminate\Http\Request;
	use Yajra\DataTables\DataTables;

	class ApplicationController extends Controller {
		public function dashboardView() {
			return view('applications.dashboard');
		}

		public function studentsView() {
			return view('applications.students', ['partners' => Partner::select(['id', 'name'])->get()]);
		}

		public function studentTableList() {
			$students = Student::select(['id', 'name', 'email', 'phone', 'test_date', 'test_result'])->get();
			return DataTables::of($students)
			                 ->addColumn('test_date', function ($student) {
				                 setlocale(LC_TIME, 'fr_FR');
				                 return !empty($student->test_date) ? $student->test_date->format('l j F Y') : '';
			                 })
			                 ->addColumn('action', function ($student) {
				                 return '
					<div class="btn-group">
                        <button type="button" class="btn bg-gradient-primary open-update-modal" data-route="' . route('app.list.students.get') . '" data-id="' . $student->id . '">
                            <i class="fas fa-pen"></i>
                        </button>
                     </div>';
			                 })->make(true);
		}

		public function studentGet(Request $request) {
			$student = Student::find($request->input('id'));
			// Faites quelque chose avec l'objet $student (par exemple, le renvoyer en tant que JSON)
			return $student;
		}

		public function studentsForm(SaveStudentRequest $request) {
			$validated = $request->validated();
			return empty($validated['id']) ? Student::create($validated) : Student::where('id', $validated['id'])->update($validated);
		}

		public function partnersView() {
			return view('applications.partners', ['students' => Student::select(['name', 'id'])->get()]);
		}

		public function partnerTableList() {
			$partners = Partner::select(['id', 'name', 'owner'])->get();
			return DataTables::of($partners)
			                 ->addColumn('action', function ($partner) {
				                 return '
					<div class="btn-group">
                        <button type="button" class="btn bg-gradient-primary open-update-modal" data-route="' . route('app.list.partners.get') . '" data-id="' . $partner->id . '">
                            <i class="fas fa-pen"></i>
                        </button>
                     </div>';
			                 })->make(true);
		}

		public function partnerGet(Request $request) {
			$partner = Partner::find($request->input('id'));
			$students = Student::select(['id'])->where(['partner_id' => $partner->id])->get();
			$output = [
				'id'       => $partner->id,
				'name'     => $partner->name,
				'owner'    => $partner->owner,
				'students' => array_map(function ($student) {
					return $student['id'];
				}, $students->toArray()),
			];
			return $output;
		}

		public function partnersForm(PartnerRequest $request) {
			$validated = $request->validated();
			$validated['students'] = $validated['students'] ?? [];
			if (empty($validated['id'])) {
				$formation = FormationType::create(['name' => $validated['name'] . ' formation']);
				$partner = new Partner($validated);
				$formation->partner()->save($partner);
			} else {
				$partner = Partner::find($validated['id']);
				$students = Student::where(['partner_id' => $partner->id]);
				$excludeStudents = [];
				foreach ($students as $student) {
					if (!in_array($student->id, $validated['students'])) {
						$excludeStudents[] = $student->id;
					}
				}
				Student::whereIn('id', $excludeStudents)->update(['partner_id' => null]);
				$partner->update($validated);
			}
			Student::whereIn('id', $validated['students'])->update(['partner_id' => $partner->id]);
			return $partner;
		}

        // formation
		public function formationsView() {
			return view('applications.formations', ['partners' => Partner::select(['name', 'id'])->get()]);
		}

		public function formationTableList(Request $request) {
			$formationTypes = FormationType::select(['id', 'name']);
			return DataTables::of($formationTypes)
			                 ->addColumn('subcategories', function (FormationType $formationType) {
				                 $subcategories = $formationType->formationSubCategories->toArray();
				                 return implode('', array_map(function ($subcategory) {
					                 return view('partials.formation-subcategory', [
						                 'modality'    => $subcategory['modality'],
						                 'formula'     => $subcategory['formula'],
						                 'convenience' => $subcategory['convenience'],
						                 'timeRange'   => $subcategory['time_range'],
						                 'price'       => $subcategory['price'],
						                 'isMonthly'   => $subcategory['is_monthly'] ? 'Payment mensuel' : 'Payment par session',
						                 'isEditable'  => $subcategory['is_editable'] ? 'Tarif modifiable' : 'Tarif fixe'
					                 ])->render();
				                 }, $subcategories));
			                 })
			                 ->addColumn('availability', function (FormationType $formationType) {
				                 $partner = $formationType->partner;
				                 return !empty($partner) ? $partner->name : 'Tout le monde';
			                 })
			                 ->addColumn('action', function ($formationType) {
				                 return view('partials.datatable-formation-type-action-button', [
					                 'id' => $formationType->id
				                 ])->render();
			                 })
			                 ->rawColumns(['action', 'subcategories'])->make(true);
		}

		public function formationGet(Request $request) {
			$formation = FormationType::find($request->input('id'));
			$output = $formation->toArray();
			$output['subcategories'] = $formation->formationSubCategories;
			$output['partner_id'] = !empty($formation->partner) ? $formation->partner->id : null;
			return response()->json($output);
		}

		public function formationDuplicate(int $id) {
			$formation = FormationType::find($id);
			$subCategories = $formation->formationSubCategories;
			$formationClone = $formation->replicate();
			$formationClone->name .= ' - copie';
			$formationClone->save();
			foreach ($subCategories as $subCategory) {
				$subCategoryClone = $subCategory->replicate();
				$formationClone->formationSubCategories()->save($subCategoryClone);
			}
			return response()->json($formationClone);
		}

		public function formationsForm(FormationTypeRequest $request) {
			$validated = $request->validated();
			if (empty($validated['id'])) {
				$formationType = FormationType::create($validated);
				$output = $formationType->toArray();
				foreach ($validated['subcategories'] as $subcategory) {
					$subcategory = new FormationSubCategory($subcategory);
					$output['subcategories'] = $formationType->formationSubCategories()->save($subcategory)->toArray();
				}
				if (!empty($validated['partner_id'])) {
					$partner = Partner::find($validated['partner_id']);
					$output['partner'] = $formationType->partner()->save($partner)->toArray();
				}
			} else {
				$formationType = FormationType::find($validated['id']);
				$formationType->update($validated);
				$subCategories = $formationType->formationSubCategories()->select('id')->pluck('id');

				$deleteSubCategories = array_map(function ($subCategory) {
					return intval($subCategory['id']);
				}, $validated['subcategories']);
				$deleteSubCategories = array_filter($subCategories->toArray(), function ($subCategory) use ($deleteSubCategories) {
					return !in_array($subCategory, $deleteSubCategories);
				});
				FormationSubCategory::whereIn('id', $deleteSubCategories)->delete();
				$newSubCategories = array_filter($validated['subcategories'], function($subCategory) {
					return empty($subCategory['id']);
				});
                $oldSubCategories = array_filter($validated['subcategories'], function($subCategory) {
					return !empty($subCategory['id']);
				});
				$output = $formationType->toArray();
				$output['subcategories'] = [];
				foreach ($newSubCategories as $subcategory) {
					$subcategory = new FormationSubCategory($subcategory);
					$formationType->formationSubCategories()->save($subcategory);
					$output['subcategories'][] = $subcategory->toArray();
				}
                foreach ($oldSubCategories as $subcategory) {
					$subCategory = FormationSubCategory::find($subcategory['id']);
                    $subCategory->update($subcategory);
					$output['subcategories'][] = $subCategory->toArray();
				}
				$partner = $formationType->partner;
				if(!empty($partner) && empty($validated['partner_id'])) {
					$partner->update(['formation_type_id' => null]);
				}
				if (!empty($validated['partner_id']) && (empty($partner) || $partner->id !== $validated['partner_id'])){
					$partner = Partner::find($validated['partner_id']);
					$formationType->partner()->save($partner);
				}
				$output['partner_id'] = !empty($partner) ? $partner->id : null;
			}
			return response()->json($output);
		}

        // session
		public function sessionsView() {
			return view('applications.sessions', []);
		}

		public function sessionTableList(Request $request) {

		}

		public function sessionGet(Request $request) {

		}

		public function sessionDuplicate(int $id) {

		}

		public function sessionsForm(FormationTypeRequest $request) {

		}
	}
