<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveFormationTypeRequest;
use App\Http\Requests\SavePartnerRequest;
use App\Http\Requests\SaveStudentRequest;
use App\Http\Requests\SessionRequest;
use App\Models\FormationSubCategory;
use App\Models\FormationType;
use App\Models\Level;
use App\Models\Partner;
use App\Models\PaymentHistory;
use App\Models\Register;
use App\Models\Registration;
use App\Models\Session;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingType;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller {
    public function dashboardView() {
        return view('applications.dashboard');
    }

    public function studentsView() {
        return view('applications.students', [
            'partners' => Partner::select(['id', 'name'])->get()->toArray(),
            'levels'   => Level::select(['id', 'label'])->get()->toArray()
        ]);
    }

    public function studentTableList() {
        $students = Student::with([
            'level' => function ($query) {
                $query->select(['id', 'label']);
            }
        ])->get(['id', 'name', 'email', 'phone', 'test_date', 'level_id']);
        return DataTables::of($students)
            ->addColumn('test_date', function ($student) {
                setlocale(LC_TIME, 'fr_FR');
                return !empty($student->test_date) ? $student->test_date->format('l j F Y') : '';
            })
            ->addColumn('test_result', function ($student) {
                $level = $student->level;
                return !empty($level) ? $level->label : '';
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
        $student = Student::with('partners')->find($request->input('id'));
        $output = $student->toArray();
        $output['partners'] = $student->partners->map(function ($partner) {
            return $partner->id;
        });
        $output['level'] = $student->level_id;
        // Faites quelque chose avec l'objet $student (par exemple, le renvoyer en tant que JSON)
        return $output;
    }

    public function studentsForm(SaveStudentRequest $request) {
        $validated = $request->validated();
        $validatedPartners = $validated['partners'] ?? [];
        if (empty($validated['id'])) {
            $student = new Student($validated);
            $student->save();
        } else {
            $student = Student::find($validated['id']);
            $student->update($validated);
            $student->partners()->whereNotIn('id', $validatedPartners)->detach();
        }
        if (!empty($validatedPartners)) {
            $student->partners()->attach($validatedPartners);
        }
        if (!empty($validated['level'])) {
            $student->level()->associate(Level::find($validated['level']));
            $student->save();
        } elseif (!empty($student->level)) {
            $student->level()->dissociate($student->level);
            $student->save();
        }
        return $student;
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
        $partner = Partner::with('students')->find($request->input('id'));
        $output = [
            'id'       => $partner->id,
            'name'     => $partner->name,
            'owner'    => $partner->owner,
            'students' => $partner->students->map(function ($student) {
                return $student->id;
            })->toArray()
        ];
        return $output;
    }

    public function partnersForm(SavePartnerRequest $request) {
        $validated = $request->validated();
        $validated['students'] = $validated['students'] ?? [];
        if (empty($validated['id'])) {
            $formation = new Training(['name' => 'Formation - ' . $validated['name']]);
            $formation->save();
            $partner = new Partner($validated);
            $formation->partner()->save($partner);
        } else {
            $partner = Partner::find($validated['id']);
            $partner->update($validated);
            $partner->students()->whereNotIn('id', $validated['students'])->detach();
        }
        $partner->students()->attach($validated['students']);
        return $partner;
    }

    // formation
    public function formationsView() {
        return view('applications.formations', ['partners' => Partner::select(['name', 'id'])->get()]);
    }

    public function formationTableList(Request $request) {
        $trainings = Training::with([
            'trainingTypes' => function ($query) {
                $query->select([
                    'id',
                    'modality',
                    'formula',
                    'convenience',
                    'price',
                    'hourly_volume',
                    'is_monthly',
                    'training_id'
                ]);
            },
            'partner'       => function ($query) {
                $query->select([
                    'id',
                    'name',
                    'training_id'
                ]);
            }
        ])->select(['id', 'name']);

        return DataTables::of($trainings)
            ->addColumn('subcategories', function (Training $training) {
                $trainingTypes = $training->trainingTypes->toArray();
                return implode('', array_map(function ($trainingType) {
                    return view('partials.formation-subcategory', [
                        'modality'    => $trainingType['modality'],
                        'formula'     => $trainingType['formula'],
                        'convenience' => $trainingType['convenience'],
                        'timeRange'   => $trainingType['hourly_volume'],
                        'price'       => $trainingType['price'],
                        'isMonthly'   => $trainingType['is_monthly'] ? 'Payment mensuel' : 'Payment par session'
                    ])->render();
                }, $trainingTypes));
            })
            ->addColumn('availability', function (Training $training) {
                $partner = $training->partner;
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
        $training = Training::find($request->input('id'));
        $output = $training->toArray();
        $output['subcategories'] = $training->trainingTypes;
        $output['partner_id'] = !empty($training->partner) ? $training->partner->id : null;
        return response()->json($output);
    }

    public function formationDuplicate(int $id) {
        $training = Training::with([
            'trainingTypes' => function ($query) {
            }
        ])->find($id);
        $trainingTypes = $training->trainingTypes;
        $trainingClone = $training->replicate();
        $trainingClone->name .= ' - copie';
        $trainingClone->save();
        foreach ($trainingTypes as $trainingType) {
            $trainingTypeClone = $trainingType->replicate();
            $trainingClone->trainingTypes()->save($trainingTypeClone);
        }
        return response()->json($trainingClone);
    }

    public function formationsForm(SaveFormationTypeRequest $request) {
        $validated = $request->validated();
        if (empty($validated['id'])) {
            $training = new Training($validated);
            $training->save();
            $output = $training->toArray();
            foreach ($validated['subcategories'] as $subcategory) {
                $subcategory = new TrainingType($subcategory);
                $output['subcategories'] = $training->trainingTypes()->save($subcategory)->toArray();
            }
            if (!empty($validated['partner_id'])) {
                $partner = Partner::find($validated['partner_id']);
                $output['partner'] = $training->partner()->save($partner)->toArray();
            }
        } else {
            $training = Training::with('trainingTypes')->find($validated['id']);
            $training->update($validated);
            $output = $training->toArray();
            $validatedSubCategories = $validated['subcategories'];
            $oldSubCategories = array_filter($validatedSubCategories, function ($subCategory) {
                return !empty($subCategory['id']);
            });
            $newSubCategories = array_filter($validatedSubCategories, function ($subCategory) {
                return empty($subCategory['id']);
            });
            $oldSubCategoriyIds = array_map(function ($trainingType) {
                return $trainingType['id'];
            }, $oldSubCategories);

            $training->trainingTypes()->whereNotIn('id', $oldSubCategoriyIds)->delete();

            $output['subcategories'] = [];
            foreach ($newSubCategories as $trainingType) {
                $trainingType = new TrainingType($trainingType);
                $training->trainingTypes()->save($trainingType);
                $output['subcategories'][] = $trainingType->toArray();
            }
            foreach ($oldSubCategories as $subcategory) {
                $trainingType = TrainingType::find($subcategory['id']);
                $trainingType->update($subcategory);
                $output['subcategories'][] = $trainingType->toArray();
            }
            $partner = $training->partner;
            if (!empty($partner) && empty($validated['partner_id'])) {
                $training->partner()->update(['training_id' => null]);
            }
            if (!empty($validated['partner_id']) && (empty($partner) || $partner->id !== $validated['partner_id'])) {
                $partner = Partner::find($validated['partner_id']);
                $training->partner()->save($partner);
            }
            $output['partner_id'] = !empty($partner) ? $partner->id : null;
        }
        return response()->json($output);
    }

    // session
    public function sessionsView() {
        $studentsQuery = Student::getWithLatestLevel();
        $trainings = Training::getTrainingFormatedList();
        return view('applications.sessions', ['students' => $studentsQuery->toArray(), 'levels' => Level::select('id', 'label')->get(), 'trainingTypes' => $trainings->toArray()]);
    }

    public function sessionTableList(Request $request) {

    }

    public function studentRegistrationDataRequest(Request $request) {
        return null;
    }

    public function sessionGet(Request $request) {
    }

    public function sessionDuplicate(int $id) {
    }

    public function sessionsForm(SessionRequest $request) {
        $validated = $request->validated();
        $today = now();
        $output = [];
        if (empty($validated['id'])) {
            $session = new Session($validated);
            $session->save();
        } else {
            $session = Session::find($validated['id']);
            $session->update($validated);
        }
        $output['session'] = $session->toArray();
        $output['registrations'] = [];

        $validateStudents = $validated['students'] ?? [];
        foreach ($validateStudents as $validateStudent) {
            $outputRegistrations = [];
            $student = Student::find($validateStudent['id']);
            $linkExists = $student->sessions()->where('session_id', $session->id)->exists();
            $studentAmount = intval($validateStudent['amount']);
            if (!$linkExists) {
                $student->sessions()->attach($session->id, [
                    'operation_date' => $today,
                    'amount' => $studentAmount,
                    'level_id' => $validateStudent['level']
                ]);
            }
            $registrationWhere = $student->sessions()->where('session_id', $session->id)->first()->pivot->toArray();
            $registration = Registration::where($registrationWhere)->get()->first();
            $registration->trainingTypes()->detach();
            $registration->trainingTypes()->attach($validateStudent['trainingTypes']);
            $outputRegistrations = $registration->toArray();
            $outputRegistrations['student'] = $student->toArray();

            $paymentHistory = PaymentHistory::where('operation_date', $registration->operation_date)->get()->first();
            if (!empty($paymentHistory)) {
                $paymentHistory->update(['amount' => -1 * $studentAmount]);
            } else {
                $paymentHistory = new PaymentHistory([
                    'operation_date' => $today,
                    'amount' => -1 * $studentAmount
                ]);
                $registration->paymentHistories()->save($paymentHistory);
            }
            $output['registrations'][] = $outputRegistrations;
        }
        return $registration;
    }
}
