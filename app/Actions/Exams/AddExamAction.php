<?php

namespace App\Actions\Exams;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Smalot\PdfParser\Parser;
use App\Models\Exam;

class AddExamAction
{
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

    public function execute(Request $request)
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
}
