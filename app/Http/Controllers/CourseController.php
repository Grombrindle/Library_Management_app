<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Services\Courses\CourseRatingService;
use App\Actions\Courses\{
    GetTeacherCoursesAction,
    FetchCourseAction,
    FetchAllCoursesAction,
    FetchRecentCoursesAction,
    FetchRatedCoursesAction,
    FetchSubscribedCoursesAction,
    FetchRecommendedCoursesAction,
    FetchUserSubscribedCoursesAction,
    FetchCourseTeachersAction,
    FetchHomePageCoursesAction,
    CheckFavoriteCourseAction,
    FetchCourseRatingsAction,
    RateCourseAction,
    PurchaseCourseAction,
    AddCourseAction,
    EditCourseAction,
    DeleteCourseAction
};

class CourseController extends Controller
{
    public function getTeacherCourses($teacherId)
    {
        return app(GetTeacherCoursesAction::class)->execute($teacherId);
    }

    public function fetch($id)
    {
        return app(FetchCourseAction::class)->execute($id);
    }

    public function fetchall()
    {
        return app(FetchAllCoursesAction::class)->execute();
    }

    public function fetchAllRecent()
    {
        return app(FetchRecentCoursesAction::class)->execute();
    }

    public function fetchAllRated()
    {
        return app(FetchRatedCoursesAction::class)->execute();
    }

    public function fetchAllSubscribed()
    {
        return app(FetchSubscribedCoursesAction::class)->execute();
    }

    public function fetchAllRecommended()
    {
        return app(FetchRecommendedCoursesAction::class)->execute();
    }

    public function fetchAllUserSubscribed()
    {
        return app(FetchUserSubscribedCoursesAction::class)->execute();
    }

    public function fetchTeacher($id)
    {
        return app(FetchCourseTeachersAction::class)->execute($id);
    }

    public function fetchHomePage()
    {
        return app(FetchHomePageCoursesAction::class)->execute(Auth::user());
    }

    public function checkFavoriteCourse($id)
    {
        return app(CheckFavoriteCourseAction::class)->execute($id);
    }

    public function fetchRatings($id)
    {
        return app(FetchCourseRatingsAction::class)->execute($id);
    }

    public function fetchFeaturedRatings($id)
    {
        $course = Course::find($id);

        return response()->json([
            'success' => true,
            'FeaturedRatings' => $course->getFeaturedRatingsAttribute(),
        ]);

    }

    public function rate(Request $request, $id)
    {
        return app(CourseRatingService::class)->rateCourse(Auth::user(), $id, $request->all());
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

    public function coursesOverview()
    {
        return [
            'recommendedCourses' => app(FetchRecommendedCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
            'topRatedCourses' => app(FetchRatedCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
            'recentCourses' => app(FetchRecentCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
            'allCourses' => app(FetchAllCoursesAction::class)->execute()->getData(true)['courses'] ?? [],
        ];
    }
}