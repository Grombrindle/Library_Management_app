<?php

use App\Http\Controllers\CourseController;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lecture;
use Illuminate\Support\Facades\App;
use App\Models\Subject;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get(
    '/',
    [SessionController::class, 'loginView']
)->name('login');
// Route::post('/reg', [SessionController::class, 'adminlogin']);
Route::post('/weblogin', [SessionController::class, 'loginWeb']);

// Auth routes
Route::view('/login', 'auth.login')->name('web.login');
Route::view('/register', 'auth.register')->name('web.register');
Route::view('/simple-login', 'auth.simple-login')->name('web.simple-login');
Route::view('/web-login', 'auth.web-login')->name('web.web-login');
Route::view('/web-register', 'auth.web-register')->name('register');

// Named routes for forms and actions referenced in Blade files
Route::post('/login', [WebController::class, 'login'])->name('login.store');
Route::get('/admin/login', [SessionController::class, 'adminLoginView'])->name('admin.login');
Route::post('/register', [WebController::class, 'register'])->name('register.store');
Route::put('/profile/update', [WebController::class, 'update'])->name('web.profile.update');
// You may need to implement adminLoginView and register methods in SessionController if not present

// Website routes
Route::view('/home', 'website.webHome')->name('web.home');
Route::get('/webcourses', function (\Illuminate\Http\Request $request) {
    $search = $request->input('search', '');
    $category = $request->input('category', '');
    $featuredCourse = null; // You can later replace this with a query for the actual featured course
    $courses = Course::paginate(12); // Paginate results for compatibility with appends/links in Blade
    return view('website.webCourses', compact('search', 'category', 'featuredCourse', 'courses'));
})->name('web.courses');

Route::view('/edit-profile', 'website.webEditProfile')->name('web.profile.edit');
Route::view('/favorites', 'website.webFavorites')->name('web.favorites');
Route::view('/my-courses', 'website.webMyCourses')->name('web.my-courses');
Route::view('/profile', 'website.webProfile')->name('web.profile');
Route::view('/webteachers', 'website.webTeachers')->name('web.teachers');




Route::group(['middleware' => ['auth']], function () {
    Route::get('/testdiagram', function () {
        return view('DiagramTest');
    });

    Route::get('/test', function () {
        return view('auth/login');
    });
    Route::get('/check-views', function () {
        return [
            'admin_subjects' => View::exists('Admin/FullAdmin/Subjects'),
            'teacher_subjects' => View::exists('Teacher/Subjects'),
            'admin_lectures' => View::exists('Admin/FullAdmin/Lectures'),
            'admin_universities' => View::exists('Admin/FullAdmin/Universities'),
        ];
    });
    Route::get('/subjects', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Subjects');
        elseif (Auth::user()->privileges == 0)
            return view('Teacher/Subjects');
        else
            return abort(404);
    });
    Route::get('/teachers', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Teachers');
        else
            return abort(404);
    });

    Route::get('/users', function (Request $request) {
        // Get the user IDs subscribed to the subject
        $userIDs = User::all()->pluck('id')->toArray();

        // Fetch the users
        $users = User::whereIn('id', $userIDs)->get();

        // Pagination settings
        $perPage = 10; // Number of items per page
        $currentPage = $request->input('page', 1); // Get the current page from the request
        $offset = ($currentPage - 1) * $perPage;

        // Slice the collection to get the items for the current page
        $currentPageItems = $users->slice($offset, $perPage)->values();

        // Create a LengthAwarePaginator instance
        $paginatedUsers = new LengthAwarePaginator(
            $currentPageItems, // Items for the current page
            $users->count(), // Total number of items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Additional options
        );

        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Users', ['users' => $users, 'sub' => false]);
        elseif (Auth::user()->privileges == 1)
            return view('Admin/SemiAdmin/Users', ['users' => $users]);
        else
            return abort(404);
    });
    Route::get('/admins', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Admins');
        else
            return abort(404);
    });
    Route::get('/lectures', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Lectures', ['lec' => false]);
        elseif (Auth::user()->privileges == 0)
            return view('Teacher/Lectures', ['lec' => false]);
        else
            return abort(404);
    });
    Route::get('/courses', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Courses');
        if (Auth::user()->privileges == 0)
            return view('Teacher/Courses');
        else
            return abort(404);
    });
    Route::get('/subject/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['subject' => $id]);
            return view('Admin/FullAdmin/Subject');
        } elseif (Auth::user()->privileges == 0) {
            session(['subject' => $id]);
            return view('Teacher/Subject');
        } else
            return abort(404);
    });
    Route::get('/course/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['course' => $id]);
            return view('Admin/FullAdmin/Course');
        } elseif (Auth::user()->privileges == 0) {
            session(['course' => $id]);
            return view('Teacher/Course');
        } else
            return abort(404);
    });
    Route::get('/teacher/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['teacher' => $id]);
            return view('Admin/FullAdmin/Teacher');
        } else
            return abort(404);
    });
    Route::get('/user/{id}', function ($id) {
        session(['user' => $id]);
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/User');
        elseif (Auth::user()->privileges == 1)
            return view('Admin/SemiAdmin/User');
        else
            return abort(404);
    });
    Route::get('/admin/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['admin' => $id]);
            return view('Admin/FullAdmin/Admin');
        } else
            return abort(404);
    });

    Route::get('/lecture/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['lecture' => $id]);
            return view('Admin/FullAdmin/Lecture');
        } elseif (Auth::user()->privileges == 0) {
            session(['lecture' => $id]);
            return view('Teacher/Lecture');
        } else
            return abort(404);
    });

    Route::get('/addadmin', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/AdminAdd');
        else
            return abort(404);
    });
    Route::post('/addadmin', [AdminController::class, 'add']);

    Route::get('/addlecture', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/LectureAdd');
        elseif (Auth::user()->privileges == 0)
            return view('Teacher/LectureAdd');
        else
            return abort(404);
    });

    Route::get('/subject/addcourse/{id}', function ($id) {
        if (Auth::user()->privileges == 0)
            return view('Teacher/CourseAdd', with(['subjectID' => $id]));
        else
            return abort(404);
    });
    Route::get('/course/addlecture/{id}', function ($id) {
        if (Auth::user()->privileges == 0)
            return view('Teacher/LectureAdd', with(['courseID' => $id]));
        else
            return abort(404);
    });
    Route::post('/addlecture', [LectureController::class, 'add'])->name('addlecture');

    Route::get('/addsubject', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/SubjectAdd');
        else
            return abort(404);
    });
    Route::post('/addsubject', [SubjectController::class, 'add']);

    Route::get('/addteacher', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/TeacherAdd');
        else
            return abort(404);
    });
    Route::post('/addteacher', [TeacherController::class, 'add']);

    // Route::get('/adduser', function () {
    //     if (Auth::user()->privileges == 2)
    //         return view('Admin/FullAdmin/UserAdd');
    // });
    // Route::post('/adduser', [UserController::class, 'add']);


    Route::get('/subject/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['subject' => $id]);
            return view('Admin/FullAdmin/SubjectEdit', ['teachers' => []]);
        } else
            return abort(404);
    });


    Route::put('/editsubject/{id}', [SubjectController::class, 'edit']);
    Route::delete('/deletesubject/{id}', [SubjectController::class, 'delete']);

    Route::get('/teacher/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {

            session(['teacher' => $id]);
            return view('Admin/FullAdmin/TeacherEdit', ['subjects' => []]);
        } else
            return abort(404);
    })->name('teacher.edit'); //might have to change this


    Route::put('/editteacher/{id}', [TeacherController::class, 'edit'])->name('teacher.update');
    Route::delete('/deleteteacher/{id}', [TeacherController::class, 'delete']);

    Route::get('/user/edit/{id}', function ($id) {
        session(['user' => $id]);
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/UserEdit');
        elseif (Auth::user()->privileges == 1)
            return view('Admin/SemiAdmin/UserEdit');
        else
            return abort(404);
    });

    Route::put('/edituser/{id}', [UserController::class, 'edit']);
    Route::delete('/deleteuser/{id}', [UserController::class, 'delete']);

    Route::get('/admin/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['admin' => $id]);
            if (Admin::findOrFail($id)->privileges != 0)
                return view('Admin/FullAdmin/AdminEdit', ['subjects' => []]);
            session(['teacher' => Admin::findOrFail($id)->teacher_id]);
            return redirect()->route('teacher.edit', ['id' => session('teacher')]);
        } else
            return abort(404);
    });
    Route::get('/addcourse', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/CourseAdd');
        elseif (Auth::user()->privileges == 0)
            return view('Teacher/CourseAdd');
        else
            return abort(404);
    });
    Route::post('/addcourse', [CourseController::class, 'add']);

    Route::get('/course/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['course' => $id]);
            return view('Admin/FullAdmin/CourseEdit');
        } elseif (Auth::user()->privileges == 0) {
            session(['course' => $id]);
            return view('Teacher/CourseEdit');
        } else
            return abort(404);
    });

    Route::put('/editcourse/{id}', [CourseController::class, 'edit']);
    Route::delete('/deletecourse/{id}', [CourseController::class, 'delete']);


    Route::put('/editadmin/{id}', [AdminController::class, 'edit']);
    Route::delete('/deleteadmin/{id}', [AdminController::class, 'delete']);


    Route::get('/lecture/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['lecture' => $id]);
            return view('Admin/FullAdmin/LectureEdit');
        }
        if (Auth::user()->privileges == 0) {
            session(['lecture' => $id]);
            return view('Teacher/LectureEdit');
        } else
            return abort(404);
    });

    Route::put('/editlecture/{id}', [LectureController::class, 'edit']);
    Route::delete('/deletelecture/{id}', [LectureController::class, 'delete']);


    Route::put('/updatequiz/{id}', [QuizController::class, 'edit']);



    Route::get('/resources', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Resources');
        else
            return abort(404);
    });
    Route::get('/resource/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['resource' => $id]);
            return view('Admin/FullAdmin/Resource');
        } else
            return abort(404);
    });
    Route::get('/addresource', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/ResourceAdd');
        else
            return abort(404);
    });
    Route::post('/addresource', [ResourceController::class, 'add']);
    Route::get('/resource/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['resource' => $id]);
            return view('Admin/FullAdmin/ResourceEdit');
        } else
            return abort(404);
    });
    Route::put('/editresource/{id}', [ResourceController::class, 'edit']);
    Route::delete('/deleteresource/{id}', [ResourceController::class, 'delete']);

    Route::get('/subject/{id}/courses', function ($id, Request $request) {
        // Store the subject ID in the session
        if (Auth::user()->privileges == 2) {
            session(['subject' => $id]);

            // Get the lecture IDs for the subject
            $courseIDs = Subject::findOrFail($id)->courses->pluck('id')->toArray();

            // Fetch the courses
            $courses = Course::whereIn('id', $courseIDs)->get();

            // Pagination settings
            $perPage = 10; // Number of items per page
            $currentPage = $request->input('page', 1); // Get the current page from the request
            $offset = ($currentPage - 1) * $perPage;

            // Slice the collection to get the items for the current page
            $currentPageItems = $courses->slice($offset, $perPage)->values();

            // Create a LengthAwarePaginator instance
            $paginatedCourses = new LengthAwarePaginator(
                $currentPageItems, // Items for the current page
                $courses->count(), // Total number of items
                $perPage, // Items per page
                $currentPage, // Current page
                ['path' => $request->url(), 'query' => $request->query()] // Additional options
            );
            // Pass the paginated lectures to the view
            return view('Admin/FullAdmin/Courses', ['courses' => $courses, 'subjectID' => $id]);
        } elseif (Auth::user()->privileges == 0) {
            // Get courses that belong to both the subject and the current teacher
            $courses = Course::where('subject_id', $id)
                ->where('teacher_id', Auth::user()->teacher_id)
                ->get();

            return view('Teacher/Courses', ['courses' => $courses, 'subjectID' => $id]);
        } else
            return abort(404);
    });

    Route::get('/course/{id}/users', function ($id, Request $request) {
        if (Auth::user()->privileges == 2) {
            session(['course' => $id]);

            // Get the user IDs subscribed to the course
            $userIDs = Course::findOrFail($id)->users->pluck('id')->toArray();

            // Fetch the users
            $users = User::whereIn('id', $userIDs)->get();

            // Pagination settings
            $perPage = 10; // Number of items per page
            $currentPage = $request->input('page', 1); // Get the current page from the request
            $offset = ($currentPage - 1) * $perPage;

            // Slice the collection to get the items for the current page
            $currentPageItems = $users->slice($offset, $perPage)->values();

            // Create a LengthAwarePaginator instance
            $paginatedUsers = new LengthAwarePaginator(
                $currentPageItems, // Items for the current page
                $users->count(), // Total number of items
                $perPage, // Items per page
                $currentPage, // Current page
                ['path' => $request->url(), 'query' => $request->query()] // Additional options
            );

            // Pass the paginated users to the view
            return view('Admin/FullAdmin/Users', ['users' => $paginatedUsers, 'sub' => true]);
        } else
            return abort(404);
    });

    Route::get('/course/{id}/lectures', function ($id, Request $request) {
        // Store the subject ID in the session
        session(['course' => $id]);

        // Get the lecture IDs for the course
        $lectureIDs = Course::findOrFail($id)->lectures->pluck('id')->toArray();

        // Fetch the lectures
        $lectures = Lecture::whereIn('id', $lectureIDs)->get();

        // Pagination settings
        $perPage = 10; // Number of items per page
        $currentPage = $request->input('page', 1); // Get the current page from the request
        $offset = ($currentPage - 1) * $perPage;

        // Slice the collection to get the items for the current page
        $currentPageItems = $lectures->slice($offset, $perPage)->values();

        // Create a LengthAwarePaginator instance
        $paginatedLectures = new LengthAwarePaginator(
            $currentPageItems, // Items for the current page
            $lectures->count(), // Total number of items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Additional options
        );

        if (Auth::user()->privileges == 2) {
            // Pass the paginated lectures to the view
            return view('Admin/FullAdmin/Lectures', ['lectures' => $paginatedLectures, 'lec' => true]);
        } elseif (Auth::user()->privileges == 0) {
            return view('Teacher/Lectures', ['lectures' => $paginatedLectures, 'lec' => true]);
        } else
            return abort(404);
    });
    Route::get('/user/{id}/lectures', function ($id, Request $request) {
        // Store the subject ID in the session
        if (Auth::user()->privileges == 2) {
            session(['user' => $id]);

            // Get the lecture IDs for the subject
            $lectureIDs = User::findOrFail($id)->lectures->pluck('id')->toArray();

            // Fetch the lectures
            $lectures = Lecture::whereIn('id', $lectureIDs)->get();

            // Pagination settings
            $perPage = 10; // Number of items per page
            $currentPage = $request->input('page', 1); // Get the current page from the request
            $offset = ($currentPage - 1) * $perPage;

            // Slice the collection to get the items for the current page
            $currentPageItems = $lectures->slice($offset, $perPage)->values();

            // Create a LengthAwarePaginator instance
            $paginatedLectures = new LengthAwarePaginator(
                $currentPageItems, // Items for the current page
                $lectures->count(), // Total number of items
                $perPage, // Items per page
                $currentPage, // Current page
                ['path' => $request->url(), 'query' => $request->query()] // Additional options
            );

            // Pass the paginated lectures to the view
            return view('Admin/FullAdmin/Lectures', ['lectures' => $paginatedLectures, 'lec' => true, 'user' => true]);
        } else
            return abort(404);
    });

    Route::put('/deletesubs', [UserController::class, 'deleteSubs']);

    // Route::get('/test', function () {
    //     dd(Subject::withCount('users')->find(16));
    // });
    Route::get('lecture/show/{id}/360', [FileController::class, 'show360'])->name('file360.show');
    Route::get('lecture/show/{id}/720', [FileController::class, 'show720'])->name('file720.show');
    Route::get('lecture/show/{id}/1080', [FileController::class, 'show1080'])->name('file1080.show');
    Route::get('lecture/show/{id}/pdf', [FileController::class, 'showPDF'])->name('filepdf.show');

    Route::get('resource/show/{id}/pdf', [FileController::class, 'showResourcePDF'])->name('filepdfresource.show');
    Route::get('resource/show/{id}/audio', [FileController::class, 'showResourceAudio'])->name('fileaudioresource.show');

    Route::get('/welcome', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/welcome');
        else if (Auth::user()->privileges == 1)
            return view('Admin/SemiAdmin/welcome');
        else if (Auth::user()->privileges == 0)
            return view('Teacher/welcome');
        else
            return redirect('/');
    })->name('welcome');
    Route::get('/confirmupdate', function () {
        return view(view: 'confirmedUpdate');
    })->name('update.confirmation');

    Route::get('/confirmadd', function () {
        return view(view: 'confirmedAdd');
    })->name('add.confirmation');

    Route::get('/confirmdelete', function () {
        return view(view: 'confirmedDelete');
    })->name('delete.confirmation');


    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'fr', 'de', 'tr', 'es'])) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('lang.switch');


    Route::get('/confirmlogout', function () {
        return view(view: 'confirmedLogout');
    })->name('logout.confirmation');

    Route::post('/logout', function (Request $request) {
        return redirect()->route('logout.confirmation');
    });
    Route::post('/registerout', [AdminController::class, 'logout']);

    Route::get('/resources', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/Resources');
        else
            return abort(404);
    });
    Route::get('/resource/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['resource' => $id]);
            return view('Admin/FullAdmin/Resource');
        } else
            return abort(404);
    });
    Route::get('/addresource', function () {
        if (Auth::user()->privileges == 2)
            return view('Admin/FullAdmin/ResourceAdd');
        else
            return abort(404);
    });
    Route::post('/addresource', [\App\Http\Controllers\ResourceController::class, 'add']);
    Route::get('/resource/edit/{id}', function ($id) {
        if (Auth::user()->privileges == 2) {
            session(['resource' => $id]);
            return view('Admin/FullAdmin/ResourceEdit');
        } else
            return abort(404);
    });
    Route::put('/editresource/{id}', [\App\Http\Controllers\ResourceController::class, 'edit']);

    // Course Request Approval Workflow
    Route::group(['prefix' => 'teacher/course-requests', 'as' => 'teacher.course_requests.'], function () {
        Route::get('/show', [\App\Http\Controllers\CourseRequestController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\CourseRequestController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\CourseRequestController::class, 'store'])->name('store');
        Route::get('/{id}', function ($id) {
            session(['courseRequest' => $id]);
            return app(\App\Http\Controllers\CourseRequestController::class)->show($id);
        })->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\CourseRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\CourseRequestController::class, 'update'])->name('update');
    });

    // Admin routes (privileges == 2)
    Route::group(['prefix' => 'admin/course-requests', 'as' => 'admin.course_requests.'], function () {
        Route::get('/show', [\App\Http\Controllers\CourseRequestController::class, 'adminIndex'])->name('index');
        Route::get('/{id}', function ($id) {
            session(['courseRequest' => $id]);
            return app(\App\Http\Controllers\CourseRequestController::class)->adminShow($id);
        })->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\CourseRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\CourseRequestController::class, 'reject'])->name('reject');
    });
});
