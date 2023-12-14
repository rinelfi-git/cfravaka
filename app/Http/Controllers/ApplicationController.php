<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Http\Requests\SaveStudentRequest;
use App\Models\FormationType;
use App\Models\Partner;
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
            'id' => $partner->id,
            'name' => $partner->name,
            'owner' => $partner->owner,
            'students' => array_map(function($student){
                return $student['id'];
            }, $students->toArray()),
        ];
        // Faites quelque chose avec l'objet $partner (par exemple, le renvoyer en tant que JSON)
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

    public function formationsView() {
        return view('applications.formations', ['partners' => Partner::select(['name', 'id'])->get()]);
    }

    public function formationTableList() {
        return null;
    }

    public function formationGet(Request $request) {
        $formation = Partner::find($request->input('id'));
        // Faites quelque chose avec l'objet $formation (par exemple, le renvoyer en tant que JSON)
        return $formation;
    }

    public function formationsForm(PartnerRequest $request) {
        $validated = $request->validated();
        if (empty($validated['id'])) {
            $formation = Partner::create($validated);
        }
        return $validated;
    }
}
