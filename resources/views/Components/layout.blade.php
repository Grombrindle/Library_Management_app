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

    <link rel="icon" href="{{ asset('Images/Web/favicon.ico') }}" sizes="any">

    <link rel="icon" href="{{ asset('Images/Web/favicon.svg') }}" type="image/svg+xml">

    <link rel="icon" href="{{ asset('Images/Web/favicon-32x32.png') }}" type="image/png" sizes="32x32">
    <link rel="icon" href="{{ asset('Images/Web/favicon-16x16.png') }}" type="image/png" sizes="16x16">

    <link rel="apple-touch-icon" href="{{ asset('Web/apple-touch-icon.png') }}" sizes="180x180">

    {{--
    <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mind Spark</title>

    <style>
        html {
            font-size: 11px;
        }

        /* Responsive font scaling */
        @media (max-width: 1200px) {
            html {
                font-size: 10px;
            }
        }

        @media (max-width: 992px) {
            html {
                font-size: 9px;
            }
        }

        @media (max-width: 768px) {
            html {
                font-size: 8px;
            }
        }

        @media (max-width: 480px) {
            html {
                font-size: 7px;
            }
        }

        body {
            margin: 0;
            overflow-x: hidden;
            height: fit-content;
            background: linear-gradient(45deg, var(--bg-gradient-start) 0%, var(--bg-gradient-start) 30%, var(--bg-gradient-end) 60%, var(--bg-gradient-end) 70%, var(--bg-gradient-end) 100%);
            font-family: Arial, Helvetica, sans-serif;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Pridi';
            background-size: 175% 175%;
            background-repeat: no-repeat;
            animation: gradientShift 5s infinite;
        }

        /* Style for all select elements */
        select {
            /* Basic styling */
            padding: 1rem 1rem;
            border: 2px solid #9997BC;
            border-radius: 20px;
            /* Rounded corners */
            background-color: #9997BC;
            /* Background color */
            color: black;
            /* Text color */
            font-size: 16px;
            cursor: pointer;
            outline: none;
            appearance: none;
            /* Remove default styling */
            -webkit-appearance: none;
            /* For Safari */
            -moz-appearance: none;
            /* For Firefox */

            /* Add a custom dropdown arrow */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
            padding-right: 35px;

            /* Transition for smooth effects */
            transition: all 0.3s ease;
        }

        /* Hover state */
        select:hover {
            background-color: var(--dropdown-bg);
            transition: all 0.3s ease;
            /* border-color: var(--card-border); */
        }

        /* Focus state */
        select:focus {
            /* border-color: #; */
            box-shadow: 0 0 0 2px rgba(153, 151, 188, 0.3);
        }

        select option {
            background-color: var(--dropdown-bg);
            color: var(--text-color);
            padding: 10px;
        }

        /* Style for dropdown menu appearance */
        select {
            transition: opacity 1s ease, transform 1s ease;
        }

        select option:hover {
            background-color: var(--dropdown-bg);
            color: var(--text-color);
        }

        /* For browsers that support the ::backdrop pseudo-element */
        select::backdrop {
            background-color: rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease;
        }

        .error {
            color: red;
            font-size: 1rem;
            margin-top: 5px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Hide the actual file input */
        .hidden-file-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        @media(max-width:1600px) {
            .file-input-label {
                width: 10rem;
            }
        }

        /* Style the label to look like your select */
        .file-input-label {
            /* Match your select styles */
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

            /* Match your arrow styling */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        /* Hover state */
        .file-input-label:hover {
            background-color: #5a8bb8;
            border-color: #5a8bb8;
        }

        .file-input-label:disabled {
            background: none;
        }

        /* Focus state */
        .hidden-file-input:focus+.file-input-label {
            border-color: #4a7aa3;
            box-shadow: 0 0 0 2px rgba(102, 153, 204, 0.3);
        }

        /* Disabled state for the entire container */
        .custom-file-input input[disabled]~.file-input-label {
            background-color: #f0f0f0;
            /* Light gray background */
            border-color: #cccccc;
            /* Lighter border */
            color: #888888;
            /* Muted text color */
            cursor: not-allowed;
            /* Show "not allowed" cursor */
            background-image: none;
            /* Remove the arrow icon */
        }

        /* Hover state should be neutral when disabled */
        .custom-file-input input[disabled]~.file-input-label:hover {
            background-color: #f0f0f0;
            border-color: #cccccc;
        }

        /* Text display when disabled */
        .custom-file-input input[disabled]~.file-input-label .file-input-text {
            color: #666666;
            font-style: italic;
        }

        /* Show selected filename */
        .file-input-text::after {
            content: attr(data-file);
            margin-left: 10px;
            font-style: italic;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        [dir="rtl"] {
            text-align: right;
        }

        [dir="rtl"] .text-left {
            text-align: right !important;
        }

        [dir="rtl"] .text-right {
            text-align: left !important;
        }

        [dir="rtl"] .float-left {
            float: right !important;
        }

        [dir="rtl"] .float-right {
            float: left !important;
        }

        [dir="rtl"] .ml-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .mr-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        [dir="rtl"] .pl-4 {
            padding-right: 1rem !important;
            padding-left: 0 !important;
        }

        [dir="rtl"] .pr-4 {
            padding-left: 1rem !important;
            padding-right: 0 !important;
        }

        [dir="rtl"] select {
            text-align: right;
            padding-right: 15px;
            padding-left: 35px;
            background-position: left 15px center;
        }

        [dir="rtl"] .file-input-label {
            text-align: right;
            padding-right: 15px;
            padding-left: 35px;
            background-position: left 15px center;
        }

        /* Dark mode styles for layout */
        .banner {
            background-color: var(--nav-bg);
            color: var(--nav-text);
        }

        .card {
            background-color: var(--dropdown-bg);
            color: var(--dropdown-text);
        }

        .card-header {
            background-color: var(--select-bg);
            color: var(--select-text);
        }

        .btn-primary {
            background-color: var(--select-bg);
            color: var(--select-text);
        }

        .btn-primary:hover {
            background-color: var(--select-hover);
        }

        .form-control {
            background-color: var(--dropdown-bg);
            color: var(--dropdown-text);
            border-color: var(--select-bg);
        }

        .form-control:focus {
            background-color: var(--dropdown-bg);
            color: var(--dropdown-text);
            border-color: var(--select-hover);
        }

        .alert {
            background-color: var(--dropdown-bg);
            color: var(--dropdown-text);
            border-color: var(--select-bg);
        }

        .table {
            color: var(--dropdown-text);
        }

        .table th {
            background-color: var(--select-bg);
            color: var(--select-text);
        }

        .table td {
            background-color: var(--dropdown-bg);
        }

        .pagination .page-link {
            background-color: var(--dropdown-bg);
            color: var(--dropdown-text);
            border-color: var(--select-bg);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--select-bg);
            color: var(--select-text);
            border-color: var(--select-bg);
        }

        /* Dark mode styles */
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
            --nav-text: #222;
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


</body>

<script>
    function setActiveLink() {
        const currentPage = window.location.pathname; // Get the current page path
        const links = document.querySelectorAll('.NavBarText'); // Get all navigation links

        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active'); // Add the 'active' class to the current page link
            } else {
                link.classList.remove('active'); // Remove 'active' class from other links
            }
        });
    }
    window.onload = setActiveLink;
</script>
<script>
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('click', function () {
            this.style.transition = 'all 0.3s ease';
        });
    });
</script>

<script>
    // Initialize theme
    document.addEventListener('DOMContentLoaded', () => {
        const theme = getCookie('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    });

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
</script>

</html>