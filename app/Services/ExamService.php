<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Smalot\PdfParser\Parser;

class ExamService
{
    /**
     * Ensure all required directories exist.
     */
    private function ensureDirectoriesExist(): void
    {
        $imagesDir = public_path('Images/Exams');
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }

        $filesDir = public_path('Files/Exams');
        if (!is_dir($filesDir)) {
            mkdir($filesDir, 0755, true);
        }
    }

    /**
     * Add a new exam.
     */
    public function addExam(Request $request)
    {
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

            // Upload image
            if ($request->hasFile('object_image')) {
                $imageFile = $request->file('object_image');
                $imageFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('Images/Exams'), $imageFilename);
                $imagePath = 'Images/Exams/' . $imageFilename;
            }

            // Upload PDF & count pages
            if ($request->hasFile('pdf_file')) {
                $pdfFile = $request->file('pdf_file');
                $pdfFilename = time() . '_exam_' . uniqid() . '.' . $pdfFile->getClientOriginalExtension();
                $pdfFile->move(public_path('Files/Exams'), $pdfFilename);
                $pdfPath = 'Files/Exams/' . $pdfFilename;

                try {
                    $parser = new Parser();
                    $pdf = $parser->parseFile(public_path($pdfPath));
                    $pages = count($pdf->getPages());
                } catch (\Exception $e) {
                    $pages = 1; // fallback
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

            session(['add_info' => ['element' => 'exam', 'id' => $exam->id, 'name' => $exam->title]]);
            session(['link' => '/exams']);
            return redirect()->route('add.confirmation');

        } catch (\Exception $e) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            if ($pdfPath && file_exists(public_path($pdfPath))) {
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
     * Edit an exam.
     */
    public function editExam(Request $request, int $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['success' => false, 'message' => 'Exam not found'], 404);
        }

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
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            if ($request->hasFile('object_image')) {
                if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl !== 'Images/Exams/default.png') {
                    unlink(public_path($exam->thumbnailUrl));
                }
                $imageFile = $request->file('object_image');
                $imageFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('Images/Exams'), $imageFilename);
                $exam->thumbnailUrl = 'Images/Exams/' . $imageFilename;
            }

            if ($request->has('title'))
                $exam->title = $request->title;
            if ($request->has('description'))
                $exam->description = $request->description;
            if ($request->has('subject_id'))
                $exam->subject_id = $request->subject_id;
            if ($request->has('date'))
                $exam->date = $request->date;

            $exam->save();

            session(['update_info' => ['element' => 'exam', 'id' => $id, 'name' => $exam->title]]);
            session(['link' => '/exams']);
            return redirect()->route('update.confirmation');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update exam', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an exam.
     */
    public function deleteExam(int $id)
    {
        try {
            $exam = Exam::find($id);
            if (!$exam) {
                return response()->json(['success' => false, 'message' => 'Exam not found'], 404);
            }

            $name = $exam->title;

            if ($exam->thumbnailUrl && file_exists(public_path($exam->thumbnailUrl)) && $exam->thumbnailUrl !== 'Images/Exams/default.png') {
                unlink(public_path($exam->thumbnailUrl));
            }
            if ($exam->pdf && file_exists(public_path($exam->pdf)) && $exam->pdf !== 'Files/Exams/default.pdf') {
                unlink(public_path($exam->pdf));
            }

            $exam->delete();

            session(['delete_info' => ['element' => 'exam', 'name' => $name]]);
            session(['link' => '/exams']);
            return redirect()->route('delete.confirmation');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete exam', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch a single exam.
     */
    public function fetchExam(int $id)
    {
        try {
            $exam = Exam::with('subject')->find($id);
            if (!$exam) {
                return response()->json(['success' => false, 'reason' => 'Exam not found'], 404);
            }
            return response()->json(['success' => true, 'exam' => $exam], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'reason' => 'Failed to fetch exam', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch all exams.
     */
    public function fetchAllExams(Request $request)
    {
        try {
            $query = Exam::with('subject');

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('date_from'))
                $query->where('date', '>=', $request->date_from);
            if ($request->has('date_to'))
                $query->where('date', '<=', $request->date_to);

            return response()->json(['success' => true, 'exams' => $query->get()], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch exams', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch exams from a subject.
     */
    public function fetchExamsFromSubject(Request $request, int $subjectId)
    {
        try {
            $subject = Subject::find($subjectId);
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
            }

            $query = Exam::with('subject')->where('subject_id', $subjectId);

            $sortBy = $request->get('sort_by', 'date');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('date_from'))
                $query->where('date', '>=', $request->date_from);
            if ($request->has('date_to'))
                $query->where('date', '<=', $request->date_to);

            return response()->json(['success' => true, 'exams' => $query->get(), 'subject' => $subject], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch exams from subject', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch exams from a year.
     */
    public function fetchExamsFromYear(Request $request, int $year)
    {
        try {
            if (!is_numeric($year) || $year < 1900 || $year > date('Y') + 10) {
                return response()->json(['success' => false, 'message' => 'Invalid year provided'], 400);
            }

            $query = Exam::with('subject')->whereYear('date', $year);

            $sortBy = $request->get('sort_by', 'date');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedSortFields = ['title', 'date', 'pages', 'created_at', 'updated_at'];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('subject_id'))
                $query->where('subject_id', $request->subject_id);
            if ($request->has('month') && $request->month >= 1 && $request->month <= 12) {
                $query->whereMonth('date', $request->month);
            }

            return response()->json(['success' => true, 'exams' => $query->get(), 'year' => $year], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch exams from year', 'error' => $e->getMessage()], 500);
        }
    }
}
