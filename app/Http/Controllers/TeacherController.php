<?php

namespace App\Http\Controllers;

use App\Services\TeacherService;
use App\Services\TeacherRatingService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Admin;
use App\Models\Teacher;

use Illuminate\Support\Facades\Validator;

use App\Actions\Teachers\{
    AddTeacherAction,
    EditTeacherAction,
    DeleteTeacherAction,
};

class TeacherController extends Controller
{
    protected $teacherService;
    protected $teacherRatingService;

    public function __construct(
        TeacherService $teacherService,
        TeacherRatingService $teacherRatingService
    ) {
        $this->teacherService = $teacherService;
        $this->teacherRatingService = $teacherRatingService;
    }

    public function fetch($id)
    {
        return $this->teacherService->fetch($id);
    }

    public function fetchCourses($id)
    {
        return $this->teacherService->fetchCourses($id);
    }

    public function fetchCoursesRecent($id)
    {
        return $this->teacherService->fetchRecentCourses($id);
    }

    public function fetchCoursesRated($id)
    {
        return $this->teacherService->fetchTopRatedCourses($id);
    }

    public function fetchCoursesNames($id)
    {
        return $this->teacherService->fetchCoursesNames($id);
    }

    public function fetchSubjects($id)
    {
        return $this->teacherService->fetchSubjects($id);
    }

    public function fetchSubjectsNames($id)
    {
        return $this->teacherService->fetchSubjectsNames($id);
    }

    public function fetchAll()
    {
        return $this->teacherService->fetchAll();
    }

    public function fetchRecentCourses($id)
    {
        return $this->teacherService->fetchRecentCourses($id);
    }

    public function fetchTopRatedCourses($id)
    {
        return $this->teacherService->fetchTopRatedCourses($id);
    }

    public function checkFavoriteTeacher($id)
    {
        return $this->teacherService->checkFavoriteTeacher($id);
    }

    public function fetchRatings($id)
    {
        return $this->teacherRatingService->fetchRatings($id);
    }

    public function fetchFeaturedRatings($id)
    {
        return $this->teacherRatingService->getFeaturedRatings($id);
    }

    public function rate(Request $request, $id)
    {
        return $this->teacherRatingService->rate($id, $request->rating, $request->review);
    }

    public function add(Request $request, $id, $file = null)
    {


        $teacher = Teacher::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'teacher_name' => [
                    Rule::unique('admins', 'name'),
                    Rule::unique('teachers', 'name')
                ],
                'teacher_user_name' => 'required|unique:admins,userName',

                'teacher_number' => [
                    Rule::unique('admins', 'number'),
                    Rule::unique('users', 'number')
                ],
            ],
            [
                'teacher_name' => __('messages.name_taken'),
                'teacher_user_name' => __('messages.username_taken'),
                'teacher_number' => __('messages.number_taken')
            ]
        );


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        return app(AddTeacherAction::class)->execute($request, $file);
    }
    public function edit(Request $request, $id, $file = null)
    {
        $teacher = Teacher::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'teacher_name' => [
                    'required',
                    Rule::unique('admins', 'name')->ignore(Admin::where('teacher_id', $teacher->id)->first()),
                    Rule::unique('teachers', 'name')->ignore($id)
                ],
                'teacher_user_name' => [
                    'required',
                    Rule::unique('admins', 'userName')->ignore(Admin::where('teacher_id', $teacher->id)->first()),
                ],

                'teacher_number' => [
                    'required',
                    Rule::unique('admins', 'number')->ignore(Admin::where('teacher_id', $teacher->id)->first()),
                    Rule::unique('users', 'number')
                ],
            ],
            [
                'teacher_name' => __('messages.name_taken'),
                'teacher_user_name' => __('messages.username_taken'),
                'teacher_number' => __('messages.number_taken')
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
        return app(EditTeacherAction::class)->execute($request, $id, $file);
    }

    public function delete($id)
    {
        return app(DeleteTeacherAction::class)->execute($id);
    }
}
