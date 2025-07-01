<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\DB;
use getID3;

class LectureController extends Controller
{
    private function ensureDirectoriesExist()
    {
        // Create base Files directory if it doesn't exist
        $baseDir = public_path('Files');
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        // Create video quality directories
        $videoDirs = ['360', '720', '1080'];
        foreach ($videoDirs as $dir) {
            $path = public_path("Files/{$dir}");
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        // Create PDF directory
        $pdfDir = public_path('Files/PDFs');
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }
    }

    public function fetch($id)
    {
        $lec = Lecture::find($id);
        $lec->rating = DB::table('lecture_rating')->where('user_id', Auth::user()->id)->where('lecture_id', $lec->id)->avg('rating');

        if ($lec) {
            return response()->json([
                'success' => "true",
                'lecture' => $lec
            ]);
        } else {
            return response()->json([
                'success' => "false",
                'reason' => "Lecture Not Found",
            ]);
        }
    }

    public function fetchRatings($id) {
        $ratings = DB::table('lecture_rating')->where('lecture_id', $id)->get();
        return response()->json([
            'ratings' => $ratings
        ]);
    }

    public function fetchFile360($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture) {
            $filePath = public_path($lecture->file_360);

            if ($lecture->file_360 == null || !file_exists($filePath)) {
                return response()->json([
                    'success' => "false",
                    'reason' => "File Not Found"
                ]);
            }

            $mimeType = mime_content_type($filePath);
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
            ]);
        }
        return response()->json([
            'success' => "false",
            'reason' => "Lecture Not Found"
        ]);
    }

    // public function uploadPdf(Request $request, $lectureId)
    // {
    //     $request->validate([
    //         'pdf_file' => 'required|file|mimes:pdf|max:10240'
    //     ]);

    //     $lecture = Lecture::findOrFail($lectureId);


    //     $pdfDir = public_path('Files/PDFs');
    //     if (!file_exists($pdfDir)) {
    //         mkdir($pdfDir, 0777, true);
    //     }


    //     if ($lecture->pdf_file && file_exists(public_path($lecture->pdf_file))) {
    //         unlink(public_path($lecture->pdf_file));
    //     }


    //     $file = $request->file('pdf_file');
    //     $filename = 'lecture_' . $lectureId . '_' . time() . '.' . $file->getClientOriginalExtension();
    //     $file->move($pdfDir, $filename);
    //     $filePath = 'Files/PDFs/' . $filename;


    //     $lecture->pdf_file = $filePath;
    //     $lecture->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'PDF uploaded successfully',
    //         'pdf_path' => $filePath
    //     ]);
    // }

    // Similar changes for fetchFile720 and fetchFile1080
    public function fetchFile720($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture) {
            $filePath = public_path($lecture->file_720);

            if ($lecture->file_720 == null || !file_exists($filePath)) {
                return response()->json([
                    'success' => "false",
                    'reason' => "File Not Found"
                ]);
            }

            $mimeType = mime_content_type($filePath);
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
            ]);
        }
        return response()->json([
            'success' => "false",
            'reason' => "Lecture Not Found"
        ]);
    }

    public function fetchFile1080($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture) {
            $filePath = public_path($lecture->file_1080);

            if ($lecture->file_1080 == null || !file_exists($filePath)) {
                return response()->json([
                    'success' => "false",
                    'reason' => "File Not Found"
                ]);
            }

            $mimeType = mime_content_type($filePath);
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
            ]);
        }
        return response()->json([
            'success' => "false",
            'reason' => "Lecture Not Found"
        ]);
    }

    public function fetchPdf($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture && $lecture->file_pdf) {
            $filePath = public_path($lecture->file_pdf);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => "false",
                    'reason' => "File Not Found"
                ]);
            }

            $mimeType = mime_content_type($filePath);
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
            ]);
        }
        return response()->json([
            'success' => "false",
            'reason' => "Lecture Not Found"
        ]);
    }

    public function rate(Request $request, $id)
    {
        $lecture = Lecture::find($id);

        if ($lecture) {
            $rate = DB::table('lecture_rating')->updateOrInsert(
                [
                    'user_id' => Auth::user()->id,
                    'lecture_id' => $id
                ],
                [
                    'rating' => $request->input('rating'),
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Course not found'
        ], 404);
    }

    public function add(Request $request)
    {

        // dd("s");
        // Ensure all required directories exist
        $this->ensureDirectoriesExist();




        $name = $request->input('lecture_name');
        $description = $request->input('lecture_description');
        $course_id = $request->input('course');
        $type = $request->hasFile('lecture_file_pdf') ? 0 : 1; // 0 for PDF, 1 for video
        $duration = $request->input('duration');
        $pages = $request->input('pages');

        if ($request->hasFile('object_image')) {
            // Store new image in public/Images/Lectures
            $file = $request->file('object_image');
            $directory = 'Images/Lectures';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;  // "Images/Lectures/filename.ext"
        } else {
            // Use default image
            $path = "Images/Lectures/default.png";
        }

        // Handle video uploads (public)
        $filePath360 = null;
        $filePath720 = null;
        $filePath1080 = null;

        if ($request->hasFile('lecture_file_360')) {
            $file360 = $request->file('lecture_file_360');
            $fileName360 = time() . '_360_' . $file360->getClientOriginalName();
            $file360->move(public_path('Files/360'), $fileName360);
            $filePath360 = 'Files/360/' . $fileName360;
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($filePath360);
            $duration = $fileInfo['playtime_seconds']; // Duration in seconds
        }

        if ($request->hasFile('lecture_file_720')) {
            $file720 = $request->file('lecture_file_720');
            $fileName720 = time() . '_720_' . $file720->getClientOriginalName();
            $file720->move(public_path('Files/720'), $fileName720);
            $filePath720 = 'Files/720/' . $fileName720;
        }

        if ($request->hasFile('lecture_file_1080')) {
            $file1080 = $request->file('lecture_file_1080');
            $fileName1080 = time() . '_1080_' . $file1080->getClientOriginalName();
            $file1080->move(public_path('Files/1080'), $fileName1080);
            $filePath1080 = 'Files/1080/' . $fileName1080;
        }

        $filePathPdf = null;
        if ($request->hasFile('lecture_file_pdf')) {
            $pdfDir = public_path('Files/PDFs');
            if (!file_exists($pdfDir)) {
                mkdir($pdfDir, 0777, true);
            }
            $pdf = $request->file('lecture_file_pdf');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $filePathPdf = 'Files\PDFs\\' . $pdfName;
            $parser = new Parser();
            $pdf = $parser->parseFile(public_path($filePathPdf));
            $pages = $pdf->getPages();
            $pageCount = count($pages);
            $pages = $pageCount;
        }

        $lecture = Lecture::create([
            'name' => $name,
            'image' => $path,
            'description' => $description,
            'file_360' => $filePath360,
            'file_720' => $filePath720,
            'file_1080' => $filePath1080,
            'file_pdf' => $filePathPdf,
            'course_id' => $course_id,
            'pages' => $pages,
            'type' => $type,
            'duration' => $duration,

        ]);

        $quiz = Quiz::create([
            'lecture_id' => $lecture->id
        ]);

        // Course::findOrFail($course_id)->lectures()->attach($lecture->id);

        $data = ['element' => 'lecture', 'id' => $lecture->id, 'name' => $lecture->name];
        session(['add_info' => $data]);
        session(['link' => '/lectures']);
        return redirect()->route('add.confirmation');
    }

    public function edit(Request $request, $id)
    {
        $lecture = Lecture::findOrFail($id);
        $lecture->name = $request->lecture_name;
        $lecture->description = $request->lecture_description;

        if ($request->hasFile('object_image')) {
            // Store new image in public/Images/Lectures
            $file = $request->file('object_image');
            $directory = 'Images/Lectures';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Store the new image
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            // Delete old image if it's not the default
            if ($lecture->image != "Images/Lectures/default.png" && file_exists(public_path($lecture->image))) {
                unlink(public_path($lecture->image));
            }

            $lecture->image = $path;
        }

        if ($request->hasFile('lecture_file_pdf')) {
            if ($lecture->file_pdf && file_exists(public_path($lecture->file_pdf))) {
                unlink(public_path($lecture->file_pdf));
            }

            $pdfDir = public_path('Files/PDFs');
            if (!file_exists($pdfDir)) {
                mkdir($pdfDir, 0777, true);
            }

            $pdf = $request->file('lecture_file_pdf');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $lecture->file_pdf = 'Files/PDFs/' . $pdfName;
            $lecture->type = 0; // PDF type
            $lecture->pages = $request->input('pages');
            $lecture->duration = null;
        }

        // Handle video updates
        if ($request->hasFile('lecture_file_360')) {
            if ($lecture->file_360 && file_exists(public_path($lecture->file_360))) {
                unlink(public_path($lecture->file_360));
            }

            $file360 = $request->file('lecture_file_360');
            $fileName360 = time() . '_360_' . $file360->getClientOriginalName();
            $file360->move(public_path('Files/360'), $fileName360);
            $lecture->file_360 = 'Files/360/' . $fileName360;
            $lecture->type = 1; // Video type
            $lecture->duration = $request->input('duration');
            $lecture->pages = null;
        }

        $lecture->save();

        $data = ['element' => 'lecture', 'id' => $lecture->id, 'name' => $lecture->name];
        session(['update_info' => $data]);
        session(['link' => '/lectures']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $lecture = Lecture::findOrFail($id);
        $name = $lecture->name;

        // Delete old image if it's not the default
        if ($lecture->image != "Images/Lectures/default.png" && file_exists(public_path($lecture->image))) {
            unlink(public_path($lecture->image));
        }

        // Delete videos from public
        if ($lecture->file_360 && file_exists(public_path($lecture->file_360)) && $lecture->video_360 != "Files/360/default_360.mp4") {
            unlink(public_path($lecture->file_360));
        }
        if ($lecture->file_720 && file_exists(public_path($lecture->file_720)) && $lecture->video_720 != "Files/360/default_720.mp4") {
            unlink(public_path($lecture->file_720));
        }
        if ($lecture->file_1080 && file_exists(public_path($lecture->file_1080)) && $lecture->video_1080 != "Files/360/default_1080.mp4") {
            unlink(public_path($lecture->file_1080));
        }
        if ($lecture->file_pdf && file_exists(public_path($lecture->file_pdf)) && $lecture->file_pdf != "Files/PDFs/default_pdf.pdf") {
            unlink(public_path($lecture->file_pdf));
        }

        $lecture->delete();

        // Update subjects lecture counts
        foreach (Subject::all() as $subject) {
            $subject->lecturesCount = Subject::withCount('lectures')->find($subject->id)->lectures_count;
            $subject->save();
        }

        $data = ['element' => 'lecture', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/lectures']);
        return redirect()->route('delete.confirmation');
    }

    public function getCourseLectures($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $lectures = $course->lectures;

        // Add score and additional data to each lecture
        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            // Add video URLs
            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;

            $lecture->rating = DB::table('lecture_rating')->where('user_id', Auth::user()->id)->where('lecture_id', $lecture->id)->avg('rating');
        });

        return response()->json([
            'success' => true,
            'lectures' => $lectures,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'subject_id' => $course->subject_id
            ]
        ]);
    }

    public function getCourseLecturesRated($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $lectures = $course->lectures()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get();

        // Add score and additional data to each lecture
        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            // Add video URLs
            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return response()->json([
            'success' => true,
            'lectures' => $lectures,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'subject_id' => $course->subject_id
            ]
        ]);
    }


    public function getCourseLecturesRecent($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $lectures = $course->lectures()
            ->withAvg('ratings', 'rating')
            ->orderByDesc('created_at')
            ->get();

        // Add score and additional data to each lecture
        $lectures->each(function ($lecture) {
            if ($lecture->quiz) {
                $score = \App\Models\score::where('user_id', Auth::id())
                    ->where('quiz_id', $lecture->quiz->id)
                    ->first();
                $lecture->score = $score ? $score->correctAnswers : null;
                $lecture->number_of_questions = $lecture->quiz->questions->count();
            } else {
                $lecture->score = null;
                $lecture->number_of_questions = 0;
            }

            // Add video URLs
            $lecture->url360 = $lecture->file_360 ? url($lecture->file_360) : null;
            $lecture->url720 = $lecture->file_720 ? url($lecture->file_720) : null;
            $lecture->url1080 = $lecture->file_1080 ? url($lecture->file_1080) : null;
            $lecture->urlpdf = $lecture->file_pdf ? url($lecture->file_pdf) : null;
        });

        return response()->json([
            'success' => true,
            'lectures' => $lectures,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'subject_id' => $course->subject_id
            ]
        ]);
    }
    public function fetchQuizQuestions($id)
    {
        $lecture = Lecture::find($id);
        if ($lecture) {
            if ($lecture->quiz) {
                return response()->json([
                    'success' => true,
                    'quiz' => $lecture->quiz->questions
                ]);
            }
            return response()->json([
                'success' => false,
                'reason' => "No Quiz For This Lesson"
            ]);
        }
        return response()->json([
            'success' => false,
            'reason' => "Lecture not Found"
        ]);
    }
}
