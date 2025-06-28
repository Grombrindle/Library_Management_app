@props(['objects' => false, 'object', 'nav' => 'true'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pridi:wght@200;300;400;500;600;700&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" href="{{ asset('Images/Web/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('Images/Web/favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('Images/Web/favicon-32x32.png') }}" type="image/png" sizes="32x32">
    <link rel="icon" href="{{ asset('Images/Web/favicon-16x16.png') }}" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" href="{{ asset('Web/apple-touch-icon.png') }}" sizes="180x180">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mind Spark</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        html { font-size: clamp(7px, 1vw + 4px, 11px); overflow-x: hidden; }
        body {
            margin: 0;
            overflow-x: hidden;
            background: linear-gradient(45deg, var(--bg-gradient-start) 0%, var(--bg-gradient-start) 30%, var(--bg-gradient-end) 60%, var(--bg-gradient-end) 70%, var(--bg-gradient-end) 100%);
            font-family: Arial, Helvetica, sans-serif;
            background-attachment: fixed;
            background-size: 200% 200%;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Pridi';
            animation: gradientShift 5s infinite ease-in-out;
            position: relative;
        }
        select {
            padding: 1rem 1rem;
            border: 2px solid #9997BC;
            border-radius: 20px;
            background-color: #9997BC;
            color: black;
            font-size: 16px;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
            padding-right: 35px;
            transition: all 0.3s ease;
        }
        select:hover { background-color: var(--dropdown-bg); transition: all 0.3s ease; }
        select:focus { box-shadow: 0 0 0 2px rgba(153, 151, 188, 0.3); }
        select option { background-color: var(--dropdown-bg); color: var(--text-color); padding: 10px; }
        select { transition: opacity 1s ease, transform 1s ease; }
        select option:hover { background-color: var(--dropdown-bg); color: var(--text-color); }
        select::backdrop { background-color: rgba(0, 0, 0, 0.1); transition: opacity 0.3s ease; }
        select:disabled { background-color: #2a2a2a; color: #666; cursor: not-allowed; opacity: 0.7; border-color: #444; }
        .error { color: red; font-size: 1rem; margin-top: 5px; text-align: center; font-family: Arial, Helvetica, sans-serif; }
        .hidden-file-input { width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1; }
        @media(max-width:1600px) { .file-input-label { width: 10rem; } }
        .file-input-label {
            display: inline-block;
            padding: 0 5rem;
            border: 2px solid #9997BC;
            border-radius: 20px;
            background-color: #9997BC;
            color: black;
            font-size: 60%;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }
        .file-input-label:hover { background-color: #5a8bb8; border-color: #5a8bb8; }
        .file-input-label:disabled { background: none; }
        .hidden-file-input:focus+.file-input-label { border-color: #4a7aa3; box-shadow: 0 0 0 2px rgba(102, 153, 204, 0.3); }
        .custom-file-input input[disabled]~.file-input-label { background-color: #f0f0f0; border-color: #cccccc; color: #888888; cursor: not-allowed; background-image: none; }
        .custom-file-input input[disabled]~.file-input-label:hover { background-color: #f0f0f0; border-color: #cccccc; }
        .custom-file-input input[disabled]~.file-input-label .file-input-text { color: #666666; font-style: italic; }
        .file-input-text::after { content: attr(data-file); margin-left: 10px; font-style: italic; }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        [dir="rtl"] { text-align: right; }
        [dir="rtl"] .text-left { text-align: right !important; }
        [dir="rtl"] .text-right { text-align: left !important; }
        [dir="rtl"] .float-left { float: right !important; }
        [dir="rtl"] .float-right { float: left !important; }
        [dir="rtl"] .ml-auto { margin-right: auto !important; margin-left: 0 !important; }
        [dir="rtl"] .mr-auto { margin-left: auto !important; margin-right: 0 !important; }
        [dir="rtl"] .pl-4 { padding-right: 1rem !important; padding-left: 0 !important; }
        [dir="rtl"] .pr-4 { padding-left: 1rem !important; padding-right: 0 !important; }
        [dir="rtl"] select { text-align: right; padding-right: 15px; padding-left: 35px; background-position: left 15px center; }
        [dir="rtl"] .file-input-label { text-align: right; padding-right: 15px; padding-left: 35px; background-position: left 15px center; }
        .banner { background-color: var(--nav-bg); color: var(--nav-text); }
        .card { background-color: var(--dropdown-bg); color: var(--dropdown-text); }
        .card-header { background-color: var(--select-bg); color: var(--select-text); }
        .btn-primary { background-color: var(--select-bg); color: var(--select-text); }
        .btn-primary:hover { background-color: var(--select-hover); }
        .form-control { background-color: var(--dropdown-bg); color: var(--dropdown-text); border-color: var(--select-bg); }
        .form-control:focus { background-color: var(--dropdown-bg); color: var(--dropdown-text); border-color: var(--select-hover); }
        .alert { background-color: var(--dropdown-bg); color: var(--dropdown-text); border-color: var(--select-bg); }
        .table { color: var(--dropdown-text); }
        .table th { background-color: var(--select-bg); color: var(--select-text); }
        .table td { background-color: var(--dropdown-bg); }
        .pagination .page-link { background-color: var(--dropdown-bg); color: var(--dropdown-text); border-color: var(--select-bg); }
        .pagination .page-item.active .page-link { background-color: var(--select-bg); color: var(--select-text); border-color: var(--select-bg); }
        :root {
            --bg-gradient-start: #555184;
            --bg-gradient-end: #FEE9CE;
            --text-color: #000;
            --text-color-inverted: #FFF;
            --nav-bg: #101010;
            --nav-text: #fff;
            --nav-hover: #9997BC;
            --select-bg: #9997BC;
            --select-text: #000;
            --select-hover: #5a8bb8;
            --dropdown-bg: #f9f9f9;
            --dropdown-text: #000;
            --dropdown-hover: #ddd;
            --filter-bg: RGBA(255, 255, 255, 0.75);
            --filter-text: #222;
            --welcome-btn: #9997BC;
            --card-bg: #555184;
            --card-border: #9997BC;
            --breadcrumb-bg: #EEE;
            --breadcrumb-border: #222;
            --dropdown-bg: #9997BC;
            --diagram-bar: black;
            --text-shadow: 0px;
        }
        [data-theme="dark"] {
            --bg-gradient-start: #2E3061;
            --bg-gradient-end: #202020;
            --text-color: #fff;
            --text-color-inverted: #000;
            --nav-bg: #000;
            --nav-text: #4A387D;
            --nav-hover: #3a5a7a;
            --select-bg: #3a5a7a;
            --select-text: #fff;
            --select-hover: #2a4a6a;
            --dropdown-bg: #1a1a1a;
            --dropdown-text: #fff;
            --dropdown-hover: #2a2a2a;
            --filter-bg: RGBA(26, 26, 26, 0.85);
            --filter-text: #DDD;
            --welcome-btn: RGBA(30, 30, 30, 0.75);
            --card-bg: RGBA(30, 30, 30, 0.75);
            --card-border: #555184;
            --breadcrumb-bg: #222;
            --breadcrumb-border: #EEE;
            --dropdown-bg: #555184;
            --diagram-bar: black;
            --text-shadow:3px;
        }
    </style>
</head>

<body style="">
    @if ($nav == 'true')
        @include('Components.NavBar')
    @endif
    @if ($objects)
        <x-banner>{{ Str::upper($object) }}</x-banner>
    @endif
    {{ $slot }}

    <div 
    x-data="{ showCourseModal: false, currentCourse: null }"
    x-on:open-course-modal.window="showCourseModal = true; currentCourse = $event.detail.course"
    x-show="showCourseModal"
    class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
    @keydown.escape.window="showCourseModal = false"
>
        <div class="bg-white rounded-2xl w-full max-w-3xl p-8" @click.away="showCourseModal = false">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-3xl font-bold" x-text="currentCourse ? currentCourse.name : ''"></h2>
                <button @click="showCourseModal = false" class="text-gray-500 hover:text-gray-700">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <template x-if="currentCourse">
                <div>
                    <img :src="currentCourse.image" :alt="currentCourse.name" class="w-full h-64 object-cover rounded-lg mb-6">
                    <div class="flex items-center mb-6">
                        <img :src="currentCourse.teacher.image" :alt="currentCourse.teacher.name" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-semibold" x-text="currentCourse.teacher.name"></p>
                            <p class="text-gray-600 text-sm" x-text="currentCourse.subject ? currentCourse.subject.name : 'General'"></p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-700" x-text="currentCourse.description"></p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.lectures_count"></p>
                            <p class="text-gray-600">Lectures</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.subscriptions_count"></p>
                            <p class="text-gray-600">Students</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.rating ? currentCourse.rating.toFixed(1) : '0.0'"></p>
                            <p class="text-gray-600">Rating</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a :href="'#'" class="bg-[#b0b0cf] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors">
                            View Course
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <div x-data="teacherModal" 
     x-show="showTeacherModal"
     x-on:open-teacher-modal.window="openModal($event.detail.teacher)"
     @keydown.escape.window="closeModal()"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="showTeacherModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="closeModal()"></div>

    <div x-show="showTeacherModal" x-transition class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col" @click.stop>
        <template x-if="currentTeacher">
            <div class="overflow-y-auto">
                <div class="p-8 text-center">
                    <img :src="currentTeacher.image" :alt="currentTeacher.name" class="w-32 h-32 rounded-full object-cover mx-auto -mt-24 border-4 border-white shadow-lg">
                    <h3 class="text-2xl font-bold mt-4" x-text="currentTeacher.name"></h3>
                    <p class="text-sm text-[#b0b0cf] font-semibold mt-1" x-text="currentTeacher.subjects"></p>
                    <p class="text-gray-600 my-4" x-text="currentTeacher.description"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-px bg-gray-200">
                    <div class="bg-white p-4 text-center">
                        <div class="text-2xl font-bold text-[#b0b0cf]" x-text="currentTeacher.courses_count"></div>
                        <p class="text-sm text-gray-600">Courses</p>
                    </div>
                    <div class="bg-white p-4 text-center">
                        <div class="text-2xl font-bold text-[#b0b0cf]" x-text="currentTeacher.favorites_count"></div>
                        <p class="text-sm text-gray-600">Favorites</p>
                    </div>
                </div>
                
                <div class="p-6 bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">Universities</h4>
                    <p class="text-gray-600 text-sm" x-text="currentTeacher.universities || 'Not specified'"></p>
                </div>

                <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b-xl">
                    <button @click="closeModal()" class="bg-[#b0b0cf] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
</body>

<script>
    document.addEventListener('alpine:init', () => {
        // Course modal
        Alpine.data('courseModal', () => ({
            showCourseModal: false,
            currentCourse: null,
            openModal(course) {
                this.currentCourse = course;
                this.showCourseModal = true;
                document.body.classList.add('overflow-hidden');
            },
            closeModal() {
                this.showCourseModal = false;
                document.body.classList.remove('overflow-hidden');
            }
        }));

        // Teacher modal
        Alpine.data('teacherModal', () => ({
            showTeacherModal: false,
            currentTeacher: null,
            openModal(teacher) {
                this.currentTeacher = teacher;
                this.showTeacherModal = true;
                document.body.classList.add('overflow-hidden');
            },
            closeModal() {
                this.showTeacherModal = false;
                document.body.classList.remove('overflow-hidden');
            }
        }));
    });
</script>
</html>
