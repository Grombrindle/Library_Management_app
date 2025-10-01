<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Services\Courses\CourseFetchService;
use App\Services\Courses\CourseRatingService;
use App\Actions\Courses\{
    CheckFavoriteCourseAction,
    PurchaseCourseAction,
    AddCourseAction,
    EditCourseAction,
    DeleteCourseAction
};

class CourseController extends Controller
{
    public function getTeacherCourses($teacherId)
    {
        return app(CourseFetchService::class)->getTeacherCourses($teacherId);
    }

    public function fetch($id)
    {
        return app(CourseFetchService::class)->fetchCourse($id);
    }

    public function fetchall()
    {
        return app(CourseFetchService::class)->fetchAllCourses();
    }

    public function fetchAllRecent()
    {
        return app(CourseFetchService::class)->fetchAllRecentCourses();
    }

    public function fetchAllRated()
    {
        return app(CourseFetchService::class)->fetchAllRatedCourses();
    }

    public function fetchAllSubscribed()
    {
        return app(CourseFetchService::class)->fetchAllSubscribedCourses();
    }

    public function fetchAllRecommended()
    {
        return app(CourseFetchService::class)->fetchAllRecommendedCourses();
    }

    public function fetchAllUserSubscribed()
    {
        return app(CourseFetchService::class)->fetchAllUserSubscribedCourses(Auth::user());
    }

    public function fetchTeacher($id)
    {
        return app(CourseFetchService::class)->fetchCourseTeachers($id);
    }

    public function fetchHomePage()
    {
        return app(CourseFetchService::class)->fetchHomePageCourses(Auth::user());
    }

    public function checkFavoriteCourse($id)
    {
        return app(CheckFavoriteCourseAction::class)->execute($id);
    }

    public function fetchRatings($id)
    {
        return app(CourseRatingService::class)->fetchRatings($id);
    }

    public function fetchFeaturedRatings($id)
    {
        return app(CourseRatingService::class)->fetchFeaturedRatings($id);
    }

    public function rate(Request $request, $id)
    {
        return app(CourseRatingService::class)->rateCourse($id, $request->all());
    }

    public function add(Request $request)
    {
        return app(AddCourseAction::class)->execute(Auth::user(), $request->all(), $request->file('object_image'));
    }

    public function edit(Request $request, $id)
    {
        return app(EditCourseAction::class)->execute($id, $request->all(), $request->file('object_image'));
    }

    public function delete($id)
    {
        return app(DeleteCourseAction::class)->execute($id);
    }

    public function purchaseCourse(Request $request, $id)
    {
        return app(PurchaseCourseAction::class)->execute(Auth::user(), $id);
    }

    // public function coursesOverview()
    // {
    //     return [
    //         'recommendedCourses' => app(FetchRecommendedCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
    //         'topRatedCourses' => app(FetchRatedCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
    //         'recentCourses' => app(FetchRecentCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
    //         'allCourses' => app(FetchAllCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
    //     ];
    // }
}