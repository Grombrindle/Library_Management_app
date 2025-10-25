<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Course;
use App\Models\Teacher;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function fetchAuth()
    {
        return $this->service->fetchAuth();
    }
    public function fetch($id)
    {
        return $this->service->fetch($id);
    }
    public function fetchCourses()
    {
        return $this->service->fetchCourses();
    }
    public function fetchLectures()
    {
        return $this->service->fetchLectures();
    }
    public function fetchSubs()
    {
        return $this->service->fetchSubs();
    }
    public function fetchAll()
    {
        return $this->service->fetchAll();
    }
    public function fetchFavoriteCourses()
    {
        return $this->service->fetchFavoriteCourses();
    }
    public function fetchFavoriteTeachers()
    {
        return $this->service->fetchFavoriteTeachers();
    }
    public function edit(Request $request, $id)
    {
        return $this->service->edit($id, $request);
    }
    public function editCounter()
    {
        return $this->service->editCounter();
    }
    public function confirmCourseSub($id)
    {
        return $this->service->confirmCourseSub($id);
    }
    public function confirmLecSub($id)
    {
        return $this->service->confirmLecSub($id);
    }
    public function toggleFavoriteCourse(Course $course)
    {
        return $this->service->toggleFavoriteCourse($course);
    }
    public function toggleFavoriteTeacher(Teacher $teacher)
    {
        return $this->service->toggleFavoriteTeacher($teacher);
    }
    public function updateUsername(Request $request)
    {
        return $this->service->updateUsername($request);
    }
    public function updatePassword(Request $request)
    {
        return $this->service->updatePassword($request);
    }
    public function updateNumber(Request $request)
    {
        return $this->service->updateNumber($request);
    }
    public function updateAvatar(Request $request)
    {
        return $this->service->updateAvatar($request);
    }
    public function seeWarning()
    {
        return $this->service->seeWarning();
    }
    public function deleteSubs()
    {
        return $this->service->deleteSubs();
    }
    public function deleteWatchlist() {
        return $this->service->deleteWatchlist();
    }
    public function delete($id)
    {
        return $this->service->delete($id);
    }
}
