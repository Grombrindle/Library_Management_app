<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SubjectService;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function fetch($id)
    {
        $result = $this->subjectService->fetch($id);
        return response()->json($result, $result['status']);
    }

    // public function fetchLectures($id)
    // {
    //     $result = $this->subjectService->fetchLectures($id);
    //     return response()->json($result, $result['status']);
    // }

    public function fetchTeachers($id)
    {
        $result = $this->subjectService->fetchTeachers($id);
        return response()->json($result, $result['status']);
    }

    public function fetchAll()
    {
        $result = $this->subjectService->fetchAll();
        return response()->json($result, $result['status']);
    }

    public function fetchScientific()
    {
        $result = $this->subjectService->fetchScientific();
        return response()->json($result, $result['status']);
    }

    public function fetchLiterary()
    {
        $result = $this->subjectService->fetchLiterary();
        return response()->json($result, $result['status']);
    }

    public function add(Request $request)
    {
        $result = $this->subjectService->add($request);

        if (isset($result['error'])) {
            return redirect()->back()->withErrors($result['error']);
        }

        $data = ['element' => 'subject', 'name' => $request->subject_name];
        session(['add_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('add.confirmation');
    }

    public function edit(Request $request, $id)
    {
        $result = $this->subjectService->edit($request, $id);

        if (isset($result['error'])) {
            return redirect()->back()->withErrors($result['error']);
        }

        $data = ['element' => 'subject', 'id' => $id, 'name' => $result->name];
        session(['update_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $name = $this->subjectService->delete($id);

        $data = ['element' => 'subject', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('delete.confirmation');
    }
}
