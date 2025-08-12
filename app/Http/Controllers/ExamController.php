<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Ensure all required directories exist for exams
     */
    private function ensureDirectoriesExist()
    {
        // Create Images directory for exam images
        $imagesDir = public_path('Images/Exams');
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }

        // Create Files directory for exam PDFs
        $filesDir = public_path('Files/Exams');
        if (!is_dir($filesDir)) {
            mkdir($filesDir, 0755, true);
        }
    }

    /**
     * Add a new exam
     */
    public function add(Request $request)
    {
        // Ensure all required directories exist
        $this->ensureDirectoriesExist();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'object_image' => 'file|mimes:jpeg,jpg,png,gif|max:2048',
            'pdf_file' => 'required|file|mimes:pdf|max:10240',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagePath = null;
            $pdfPath = null;
            $pages = 0;

            // Handle image upload
            if ($request->hasFile('object_image')) {
                $imageFile = $request->file('object_image');
                $imageFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('Images/Exams'), $imageFilename);
                $imagePath = 'Images/Exams/' . $imageFilename;
            }

            // Handle PDF file upload and count pages
            if ($request->hasFile('pdf_file')) {
                $pdfFile = $request->file('pdf_file');
                $pdfFilename = time() . '_exam_' . uniqid() . '.' . $pdfFile->getClientOriginalExtension();
                $pdfFile->move(public_path('Files/Exams'), $pdfFilename);
                $pdfPath = 'Files/Exams/' . $pdfFilename;

                // Count PDF pages using PDF parser
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile(public_path($pdfPath));
                    $pdfPages = $pdf->getPages();
                    $pages = count($pdfPages);
                } catch (\Exception $e) {
                    // If PDF parsing fails, set pages to 1 as fallback
                    $pages = 1;
                }
            }

            $exam = Exam::create([
                'title' => $request->title,
                'description' => $request->description,
                'thumbnailUrl' => $imagePath ?? "Images/Exams/default.png",
                'pdf' => $pdfPath,
                'subject_id' => $request->subject_id,
                'pages' => $pages,
                'date' => $request->date,
            ]);

            $data = ['element' => 'exam', 'id' => $request->input('id'), 'name' => $request->input('title')];
            session(['add_info' => $data]);
            session(['link' => '/exams']);
            return redirect()->route('add.confirmation');

        } catch (\Exception $e) {
            // Clean up uploaded files if exam creation fails
            if (isset($imagePath) && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            if (isset($pdfPath) && file_exists(public_path($pdfPath))) {
                unlink(public_path($pdfPath));
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit an existing exam
     */
    public function edit(Request $request, $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], 404);
        }

        // Ensure directories exist
        $this->ensureDirectoriesExist();

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'object_image' => 'sometimes|file|mimes:jpeg,jpg,png,gif|max:2048',
            'pdf_file' => 'sometimes|file|mimes:pdf|max:10240',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle image update
            if ($request->hasFile('object_image')) {
                // Delete old image if it exists and is not default
                if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl != 'Images/Exams/default.png') {
                    unlink(public_path($exam->thumbnailUrl));
                }

                $imageFile = $request->file('object_image');
                $imageFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('Images/Exams'), $imageFilename);
                $exam->thumbnailUrl = 'Images/Exams/' . $imageFilename;
            }

            // Update other fields
            if ($request->has('title')) {
                $exam->title = $request->title;
            }
            if ($request->has('description')) {
                $exam->description = $request->description;
            }
            if ($request->has('subject_id')) {
                $exam->subject_id = $request->subject_id;
            }
            if ($request->has('date')) {
                $exam->date = $request->date;
            }

            $exam->save();


            $data = ['element' => 'exam', 'id' => $id, 'name' => $exam->title];
            session(['update_info' => $data]);
            session(['link' => '/exams']);
            return redirect()->route('update.confirmation');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete an exam
     */
    public function delete($id)
    {
        try {
            $exam = Exam::find($id);

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exam not found'
                ], 404);
            }

            $name = $exam->title;

            // Delete image if it exists
            if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl != 'Images/Exams/default.pngs') {
                unlink(public_path($exam->thumbnailUrl));
            }

            // Delete PDF file if it exists
            if ($exam->pdf && file_exists(public_path($exam->pdf)) && $exam->pdf != 'Files/PDFs/default.pdf') {
                unlink(public_path($exam->pdf));
            }

            $exam->delete();


            $data = ['element' => 'exam', 'name' => $name];
            session(['delete_info' => $data]);
            session(['link' => '/exams']);
            return redirect()->route('delete.confirmation');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch a single exam by ID
     */
    public function fetch($id)
    {
        try {
            $exam = Exam::with('subject')->find($id);

            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'reason' => 'Exam not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'exam' => $exam
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reason' => 'Failed to fetch exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch all exams with optional pagination and sorting
     */
    public function fetchAll(Request $request)
    {
        try {
            $query = Exam::with('subject');

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Apply date range filter
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->where('date', '<=', $request->date_to);
            }

            // For API: always return all results, no pagination
            $exams = $query->get();
            return response()->json([
                'success' => true,
                'exams' => $exams
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch exams by subject ID
     */
    public function fetchFromSubject(Request $request, $subjectId)
    {
        try {
            // Verify subject exists
            $subject = Subject::find($subjectId);
            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject not found'
                ], 404);
            }

            $query = Exam::with('subject')->where('subject_id', $subjectId);

            // Apply sorting
            $sortBy = $request->get('sort_by', 'date');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Apply date range filter
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->where('date', '<=', $request->date_to);
            }

            // For API: always return all results, no pagination
            $exams = $query->get();
            return response()->json([
                'success' => true,
                'exams' => $exams,
                'subject' => $subject
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch exams from subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch exams by year
     */
    public function fetchFromYear(Request $request, $year)
    {
        try {
            // Validate year
            if (!is_numeric($year) || $year < 1900 || $year > date('Y') + 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid year provided'
                ], 400);
            }

            $query = Exam::with('subject')->whereYear('date', $year);

            // Apply sorting
            $sortBy = $request->get('sort_by', 'date');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Apply subject filter
            if ($request->has('subject_id') && !empty($request->subject_id)) {
                $query->where('subject_id', $request->subject_id);
            }

            // Apply month filter
            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                if ($month >= 1 && $month <= 12) {
                    $query->whereMonth('date', $month);
                }
            }

            // For API: always return all results, no pagination
            $exams = $query->get();
            return response()->json([
                'success' => true,
                'exams' => $exams,
                'year' => $year
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch exams from year',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch PDF file for an exam
     */
    // public function fetchPdf($id)
    // {
    //     try {
    //         $exam = Exam::find($id);

    //         if (!$exam) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Exam not found'
    //             ], 404);
    //         }

    //         if (!$exam->pdf || !file_exists(public_path($exam->pdf))) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'PDF file not found'
    //             ], 404);
    //         }

    //         $filePath = public_path($exam->pdf);
    //         $mimeType = mime_content_type($filePath);

    //         return response()->file($filePath, [
    //             'Content-Type' => $mimeType,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch PDF file',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Fetch image for an exam
     */
    // public function fetchImage($id)
    // {
    //     try {
    //         $exam = Exam::find($id);

    //         if (!$exam) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Exam not found'
    //             ], 404);
    //         }

    //         if (!$exam->thumbnailUrl || !file_exists(public_path($exam->thumbnailUrl))) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Image not found'
    //             ], 404);
    //         }

    //         $filePath = public_path($exam->thumbnailUrl);
    //         $mimeType = mime_content_type($filePath);

    //         return response()->file($filePath, [
    //             'Content-Type' => $mimeType,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch image',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
