<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\NotificationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Lecture;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HelpfulController;
use App\Http\Controllers\SavedMessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WatchlistController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/getuser/{id}', [UserController::class, 'fetch']);
Route::post('/register', [SessionController::class, 'createUser']);
Route::post('/login', [SessionController::class, 'loginUser']);


//test
// Route::get('/test', [TeacherController::class, 'test']);

// Route::get('/subject/{id}/lectures', function($id) {
//     return response()->json(['lectureCount' => $lectureCount]);
// });

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/getuser', [UserController::class, 'fetchAuth']);
    Route::get('/getuser/{id}', [UserController::class, 'fetch']);
    Route::get('/getusercourses', [UserController::class, 'fetchCourses']);
    Route::get('/getuserlectures', [UserController::class, 'fetchLectures']);
    Route::get('/getusersubscriptions', [UserController::class, 'fetchSubs']);
    Route::get('/getallusers', [UserController::class, 'fetchAll']);
    Route::get('/getfavoritecourses', [UserController::class, 'fetchFavoriteCourses']);
    Route::get('/getfavoriteteachers', [UserController::class, 'fetchFavoriteTeachers']);
    Route::post('/course/{course}/favorite', [UserController::class, 'toggleFavoriteCourse']);
    Route::post('/teacher/{teacher}/favorite', [UserController::class, 'toggleFavoriteTeacher']);
    Route::get('/courseissubscribed/{id}', [UserController::class, 'confirmCourseSub']);
    Route::get('/lectureissubscribed/{id}', [UserController::class, 'confirmLecSub']);
    Route::put('/counter', [UserController::class, 'editCounter']);
    Route::put('/changepassword', [UserController::class, 'updatePassword']);
    Route::put('/changenumber', [UserController::class, 'updateNumber']);
    Route::put('/changeusername', [UserController::class, 'updateUsername']);
    Route::put('/changeavatar', [UserController::class, 'updateAvatar']);

    Route::get('/getteacher/{id}', [TeacherController::class, 'fetch']);
    Route::get('/getteachersubjects/{id}', [TeacherController::class, 'fetchSubjects']);
    Route::get('/getteachercourses/{id}', [TeacherController::class, 'fetchCourses']);
    Route::get('/getteachercoursesrecent/{id}', [TeacherController::class, 'fetchCoursesRecent']);
    Route::get('/getteachercoursesrated/{id}', [TeacherController::class, 'fetchCoursesRated']);
    Route::get('/getteachersubjectsnames/{id}', [TeacherController::class, 'fetchSubjectsNames']);
    Route::get('/getteachercoursessnames/{id}', [TeacherController::class, 'fetchCoursesNames']);
    // Route::get('/getteacheruniversities/{id}', [TeacherController::class, 'fetchUnis']);
    Route::get('/getallteachers', [TeacherController::class, 'fetchAll']);
    Route::get('/favoriteteacher/{teacher}', [TeacherController::class, 'checkFavoriteTeacher']);
    Route::post('/rateteacher/{id}', [TeacherController::class, 'rate']);
    // Route::get('/teachers/{teacher}/courses', [CourseController::class, 'getTeacherCourses']);

    // Route::get('/getuniversity/{id}', [UniversityController::class, 'fetch']);
    // Route::get('/getuniversityteachers/{id}', [UniversityController::class, 'fetchTeachers']);
    // Route::get('/getalluniversities', [UniversityController::class, 'fetchall']);

    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'fetchAll']);
        Route::get('/literary', [SubjectController::class, 'fetchLiterary']);
        Route::get('/scientific', [SubjectController::class, 'fetchScientific']);
        Route::get('/{id}', [SubjectController::class, 'fetch']);
        // Route::get('/{id}/lectures', [SubjectController::class, 'fetchLectures']);
        Route::get('/{id}/teachers', [SubjectController::class, 'fetchTeachers']);
        // Route::post('/', [SubjectController::class, 'add']);             We're not gonna add subjects through the API
        // Route::put('/{id}', [SubjectController::class, 'edit']);         Same here
        // Route::delete('/{id}', [SubjectController::class, 'delete']);    and here
    });

    Route::get('/getcourse/{id}', [CourseController::class, 'fetch']);
    Route::get('/getallcourses', [CourseController::class, 'fetchAll']);
    Route::get('/getallcoursesrecent', [CourseController::class, 'fetchAllRecent']);
    Route::get('/getallcoursesrated', [CourseController::class, 'fetchAllRated']);
    Route::get('/getallcoursessubscribed', [CourseController::class, 'fetchAllSubscribed']);
    Route::get('/getallcoursesusersubscribed', [CourseController::class, 'fetchAllUserSubscribed']);
    Route::get('/getallcoursesrecommended', [CourseController::class, 'fetchAllRecommended']);
    Route::get('/getallhomepage', [CourseController::class, 'fetchHomePage']);
    Route::get('/favoritecourse/{id}', [CourseController::class, 'checkFavoriteCourse']);
    Route::post('/ratecourse/{id}', [CourseController::class, 'rate']);
    Route::post('/course/{id}/purchase', [CourseController::class, 'purchaseCourse']);
    Route::get('/courses/overview', [CourseController::class, 'coursesOverview']);
    // Route::post('/course/{course}/set-price', [CourseController::class, 'setCoursePrice']);


    Route::get('/getlecture/{id}', [LectureController::class, 'fetch']);
    Route::get('/getcourselectures/{courseId}', [LectureController::class, 'getCourseLectures']);
    Route::get('/getcourselectures/{courseId}/rated', [LectureController::class, 'getCourseLecturesRated']);
    Route::get('/getcourselectures/{courseId}/recent', [LectureController::class, 'getCourseLecturesRecent']);
    Route::get('/getlecturefile360/{id}', [LectureController::class, 'fetchFile360']);
    Route::get('/getlecturefile720/{id}', [LectureController::class, 'fetchFile720']);
    Route::get('/getlecturefile1080/{id}', [LectureController::class, 'fetchFile1080']);
    Route::get('/getlecturefilepdf/{id}', [LectureController::class, 'fetchPdf']);
    Route::get('/getlecturequiz/{id}', [LectureController::class, 'fetchQuizQuestions']);
    Route::post('/ratelecture/{id}', [LectureController::class, 'rate']);
    Route::post('/incrementviews/{id}', [LectureController::class, 'incrementViews']);
    // Route::post('/lectures/{lecture}/pdf', [LectureController::class, 'uploadPdf']);     Not through the API

    Route::get('/getexam/{id}', [ExamController::class, 'fetch']);
    Route::get('/getallexams', [ExamController::class, 'fetchAll']);
    Route::get('/getallsubjectexams/{id}', [ExamController::class, 'fetchFromSubject']);
    Route::get('/getallyearexams/{year}', [ExamController::class, 'fetchFromYear']);

    Route::get('/getteacherimage/{id}', [ImageController::class, 'fetchTeacher']);
    Route::get('/getlectureimage/{id}', [ImageController::class, 'fetchLecture']);
    Route::get('/getsubjectimage/{id}', [ImageController::class, 'fetchSubject']);
    Route::get('/getcourseimage/{id}', [ImageController::class, 'fetchCourse']);
    Route::get('/getresourceimage/{id}', [ImageController::class, 'fetchResource']);
    Route::get('/getexamimage/{id}', [ImageController::class, 'fetchExam']);

    Route::get('/getscore/{id}', [QuizController::class, 'fetchScore']);
    Route::get('/getcoursescores/{id}', [QuizController::class, 'checkScores']);
    Route::post('/finishquiz/{id}', [QuizController::class, 'finish']);

    Route::get('/getresource/{id}', [ResourceController::class, 'fetch']);
    Route::get('/getsubjectresources/{id}', [ResourceController::class, 'fetchFromSubject']);
    Route::get('/getallresources', [ResourceController::class, 'fetchAll']);
    Route::get('/getallresourcesrecent', [ResourceController::class, 'fetchAllRecent']);
    Route::get('/getallresourcesrated', [ResourceController::class, 'fetchAllRated']);
    Route::get('/getallresourcesrecommended', [ResourceController::class, 'fetchAllRecommended']);
    Route::get('/getallresourcespage', [ResourceController::class, 'fetchAllPage']);
    Route::post('/rateresource/{id}', [ResourceController::class, 'rate']);


    //Notifcations
    Route::get('/customer_notifcations', [NotificationsController::class, 'getNotifications']);
    Route::post('/send_notifications', [NotificationsController::class, 'sendPushNotification']);
    Route::post('/notifications_all', [NotificationsController::class, 'sendPushNotificationToAllUsers']);
    Route::post('/update_fcm_token', [NotificationsController::class, 'updateFcmToken']);
    Route::post('/set_fcm_token', [NotificationsController::class, 'setToken']);
    Route::post('/update_notification_preferences', [NotificationsController::class, 'updateNotificationPreferences']);
    Route::get('/get_notification_preferences', [NotificationsController::class, 'getNotificationPreferences']);

    //TASKS
    Route::get('/gettasks', [TaskController::class, 'fetchAll']);
    Route::post('/addtask', [TaskController::class, 'add']);
    Route::put('/checktask/{id}', [TaskController::class, 'toggleChecked']);
    Route::put('/trashtask/{id}', [TaskController::class, 'toggleDelete']);
    Route::put('/restoretask/{id}', [TaskController::class, 'restore']);
    Route::put('/edittask/{id}', [TaskController::class, 'edit']);
    Route::delete('/deletetask/{id}', [TaskController::class, 'delete']);
    Route::get('/gettrashedtasks', [TaskController::class, 'trashedTasks']);
    Route::get('/getavailabletasks', [TaskController::class, 'availableTasks']);

    Route::get('/getwatchlistlectures', [WatchlistController::class, 'fetchLectures']);
    Route::get('/getwatchlistcourses', [WatchlistController::class, 'fetchCourses']);
    Route::get('/getwatchlistresources', [WatchlistController::class, 'fetchResources']);
    Route::post('/togglewatchlistlecture/{id}', [WatchlistController::class, 'toggleLecture']);
    Route::post('/togglewatchlistcourse/{id}', [WatchlistController::class, 'toggleCourse']);
    Route::post('/togglewatchlistresource/{id}', [WatchlistController::class, 'toggleResource']);

    Route::post('/togglehelpful', [HelpfulController::class, 'toggleHelpful']);
    Route::post('/toggleunhelpful', [HelpfulController::class, 'toggleUnhelpful']);

    Route::get('/getlikes/{id}', [LikeController::class, 'fetchLikes']);
    Route::post('/togglelike', [LikeController::class, 'toggleLike']);
    Route::post('/toggledislike', [LikeController::class, 'toggleDislike']);


    Route::get('/getcourseratings/{id}', [CourseController::class, 'fetchRatings']);
    Route::get('/getlectureratings/{id}', [LectureController::class, 'fetchRatings']);
    Route::get('/getresourceratings/{id}', [ResourceController::class, 'fetchRatings']);
    Route::get('/getteacherratings/{id}', [TeacherController::class, 'fetchRatings']);

    Route::post('/togglesaved', [SavedMessageController::class, 'toggleSaved']);

    Route::post('/report', [ReportController::class, 'add']);
    Route::put('/change', [ReportController::class, 'test']);

    // Route::get('/getuser', [SessionController::class, 'test']);
    Route::post('/logout', [SessionController::class, 'logoutUser'])->name('logout.user');
    Route::post('/ban', [SessionController::class, 'ban'])->name('ban');



    // Route::get('/url/{videoId}/{quality}', [FileController::class, 'encryptAndGenerateUrl']);

    // Route::get('/download-encrypted-video/{file}', [FileController::class, 'serveEncryptedFile'])
    //     ->name('download.encrypted.video');

});
