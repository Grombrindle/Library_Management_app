<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TeacherService;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
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

    public function rate(Request $request, $id)
    {
        return $this->teacherService->rate($id, $request->rating, $request->review);
    }

    public function add(Request $request)
    {
        return $this->teacherService->add($request->all(), $request->file('object_image'));
    }

    public function edit(Request $request, $id)
    {
        return $this->teacherService->edit($id, $request->all(), $request->file('object_image'));
    }

    public function delete($id)
    {
        return $this->teacherService->delete($id);
    }
}
