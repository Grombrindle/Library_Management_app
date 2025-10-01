<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SubjectService;

use App\Actions\Subjects\{
    AddSubjectAction,
    EditSubjectAction,
    DeleteSubjectAction,
};

class SubjectController extends Controller
{
    protected $subjectService;
    protected $addSubjectAction;
    protected $editSubjectAction;
    protected $deleteSubjectAction;

    public function __construct(
        SubjectService $subjectService,
        AddSubjectAction $addSubjectAction,
        EditSubjectAction $editSubjectAction,
        DeleteSubjectAction $deleteSubjectAction
    ) {
        $this->subjectService = $subjectService;
        $this->addSubjectAction = $addSubjectAction;
        $this->editSubjectAction = $editSubjectAction;
        $this->deleteSubjectAction = $deleteSubjectAction;
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
        $result = $this->addSubjectAction->execute($request);

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
        $result = $this->editSubjectAction->execute($request, $id);

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
        $name = $this->deleteSubjectAction->execute($id);

        $data = ['element' => 'subject', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/subjects']);
        return redirect()->route('delete.confirmation');
    }
}
