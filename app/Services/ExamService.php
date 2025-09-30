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
