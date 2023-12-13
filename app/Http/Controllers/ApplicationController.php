<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\SaveStudentRequest;
	use App\Models\Student;
	use App\Models\User;
	use Illuminate\Http\Request;
	use Yajra\DataTables\DataTables;
	
	class ApplicationController extends Controller {
		public function dashboardView() {
			return view('applications.dashboard');
		}
		
		public function studentsView() {
			return view('applications.students');
		}
		
		public function studentTableList() {
			$students = Student::select(['id', 'name', 'email', 'phone', 'test_date', 'test_result']);
			return DataTables::of($students)
				->addColumn('test_date', function($student) {
					setlocale(LC_TIME, 'fr_FR');
					return !empty($student->test_date) ? $student->test_date->format('l j F Y') : '';
				})
				->addColumn('action', function($student) {
					return '
					<div class="btn-group">
                        <button type="button" class="btn bg-gradient-primary open-update-modal" data-route="'.route('app.list.students.get').'" data-id="'.$student->id.'">
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
	}
