<?php

use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Lecture;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\FileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/getuser/{id}', [UserController::class, 'fetch']);
Route::post('/register', [SessionController::class, 'createUser']);
Route::post('/login', [SessionController::class, 'loginUser']);


//test
// Route::post('/registerteacher', [TeacherController::class, 'add']);

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
    Route::put('/changeusername', [UserController::class, 'updateUsername']);

    Route::get('/getteacher/{id}', [TeacherController::class, 'fetch']);
    Route::get('/getteachersubjects/{id}', [TeacherController::class, 'fetchSubjects']);
    Route::get('/getteachercourses/{id}', [TeacherController::class, 'fetchCourses']);
    Route::get('/getteachersubjectsnames/{id}', [TeacherController::class, 'fetchSubjectsNames']);
    Route::get('/getteachercoursessnames/{id}', [TeacherController::class, 'fetchCoursesNames']);
    // Route::get('/getteacheruniversities/{id}', [TeacherController::class, 'fetchUnis']);
    Route::get('/getallteachers', [TeacherController::class, 'fetchAll']);
    Route::get('/favoriteteacher/{teacher}', [TeacherController::class, 'checkFavoriteTeacher']);
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

    Route::get('/getallcourses', [CourseController::class, 'fetchAll']);
    Route::get('/favoritecourse/{id}', [CourseController::class, 'checkFavoriteCourse']);
    Route::post('/ratecourse/{id}', [CourseController::class, 'rate']);


    Route::get('/getlecture/{id}', [LectureController::class, 'fetch']);
    Route::get('/getcourselectures/{courseId}', [LectureController::class, 'getCourseLectures']);
    Route::get('/getlecturefile360/{id}', [LectureController::class, 'fetchFile360']);
    Route::get('/getlecturefile720/{id}', [LectureController::class, 'fetchFile720']);
    Route::get('/getlecturefile1080/{id}', [LectureController::class, 'fetchFile1080']);
    Route::get('/getlecturefilepdf/{id}', [LectureController::class, 'fetchPdf']);
    Route::get('/getlecturequiz/{id}', [LectureController::class, 'fetchQuizQuestions']);
    Route::post('/ratelecture/{id}', [LectureController::class, 'rate']);
    // Route::post('/lectures/{lecture}/pdf', [LectureController::class, 'uploadPdf']);     Not through the API

    Route::get('/getteacherimage/{id}', [ImageController::class, 'fetchTeacher']);
    Route::get('/getlectureimage/{id}', [ImageController::class, 'fetchLecture']);
    Route::get('/getsubjectimage/{id}', [ImageController::class, 'fetchSubject']);
    Route::get('/getcourseimage/{id}', [ImageController::class, 'fetchCourse']);

    Route::get('/getscore/{id}', [QuizController::class, 'fetchScore']);
    Route::get('/getcoursescores/{id}', [QuizController::class, 'checkScores']);
    Route::post('/finishquiz/{id}', [QuizController::class, 'finish']);

    // Route::get('/getuser', [SessionController::class, 'test']);
    Route::post('/logout', [SessionController::class, 'logoutUser'])->name('logout.user');
    Route::post('/ban', [SessionController::class, 'banUser'])->name('ban.user');





    // Route::get('/url/{videoId}/{quality}', [FileController::class, 'encryptAndGenerateUrl']);

    // Route::get('/download-encrypted-video/{file}', [FileController::class, 'serveEncryptedFile'])
    //     ->name('download.encrypted.video');

});
