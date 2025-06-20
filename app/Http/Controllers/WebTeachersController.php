<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\University;
use Illuminate\Support\Facades\Auth;
use App\Models\Favourite;
use Carbon\Carbon;

class WebTeachersController extends Controller
{
    /**
     * Display the teachers/professors list page
     */
    // public function index(Request $request)
    // {
    //     // Get search term if present
    //     $search = $request->input('search');
        
    //     // Base query
    //     $query = Teacher::with(['subjects', 'universities', 'courses']);
        
    //     // Apply search filter if provided
    //     if ($search) {
    //         $query->where('name', 'like', "%{$search}%")
    //             ->orWhereHas('subjects', function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%");
    //             })
    //             ->orWhereHas('universities', function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%");
    //             });
    //     }
        
    //     // Filter by subject if provided
    //     $subject = $request->input('subject');
    //     if ($subject) {
    //         $query->whereHas('subjects', function ($q) use ($subject) {
    //             $q->where('name', $subject);
    //         });
    //     }
        
    //     // Get teachers with pagination
    //     $teachers = $query->orderBy('name')->paginate(6);
        
    //     // Get user's favorite teachers if logged in
    //     $favorites = [];
    //     if (Auth::check()) {
    //         $favorites = Auth::user()->favoriteTeachers->pluck('id')->toArray();
    //     }
        
    //     // Get subjects for filtering
    //     $subjects = Subject::orderBy('name')->get();
        
    //     // Get featured teacher if any (e.g., most courses or ratings)
    //     $featuredTeacher = Teacher::withCount(['favourites' => function ($query) {
    //         $query->where('created_at', '>=', Carbon::now()->subWeek());
    //     }])
    //     ->orderBy('favourites_count', 'desc')
    //     ->with(['subjects', 'universities'])
    //     ->first();
            
    //     return view('Website.webTeachers', [
    //         'teachers' => $teachers,
    //         'subjects' => $subjects,
    //         'favorites' => $favorites,
    //         'featuredTeacher' => $featuredTeacher,
    //         'search' => $search,
    //         'subjectFilter' => $subject
    //     ]);
    // }
     public function index(Request $request)
    {
        $search = $request->input('search');

        // Query for the main list of teachers with search functionality
        $teachers = Teacher::query()
            ->with(['subjects', 'universities'])
            ->withCount('courses')
            ->withCount('favoritedByUsers') // Count favorites for each teacher
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('subjects', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('universities', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->orderByDesc('favorited_by_users_count') // Show most popular first
            ->paginate(9);

        // --- Logic to find the Featured Teacher ---
        // The "best" teacher is the one with the most favorites.
        $featuredTeacher = Teacher::query()
            ->with(['subjects', 'universities'])
            ->withCount('courses')
            ->withCount('favoritedByUsers') // Essential for sorting
            ->has('favoritedByUsers')       // Ensure they have at least one favorite
            ->orderByDesc('favorited_by_users_count')
            ->orderByDesc('courses_count') // Use course count as a tie-breaker
            ->first();

        // Fallback if no teacher has been favorited yet
        if (!$featuredTeacher) {
            $featuredTeacher = Teacher::query()
                ->with(['subjects', 'universities'])
                ->withCount('courses')
                ->withCount('favoritedByUsers')
                ->latest()
                ->first();
        }

        return view('Website.webTeachers', [ // Assuming your view is at `resources/views/web/teachers.blade.php`
            'teachers' => $teachers,
            'featuredTeacher' => $featuredTeacher,
            'search' => $search
        ]);
    }
} 