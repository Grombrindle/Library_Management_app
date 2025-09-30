<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;
use App\Models\Quiz;
use Illuminate\Http\Request;
use getID3;

class AddLectureAction
{
    private function ensureDirectoriesExist()
    {
        $baseDir = public_path('Files');
        if (!is_dir($baseDir))
            mkdir($baseDir, 0755, true);

        foreach (['360', '720', '1080'] as $dir) {
            if (!is_dir(public_path("Files/{$dir}")))
                mkdir(public_path("Files/{$dir}"), 0755, true);
        }

        $pdfDir = public_path('Files/PDFs');
        if (!is_dir($pdfDir))
            mkdir($pdfDir, 0755, true);
    }


    public function execute(Request $request)
    {

        $this->ensureDirectoriesExist();

        $name = $request->input('lecture_name');
        $description = $request->input('lecture_description');
        $course_id = $request->input('course');
        $type = $request->hasFile('lecture_file_pdf') ? 0 : 1;
        $duration = $request->input('duration');
        $pages = $request->input('pages');

        // Handle image
        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $directory = 'Images/Lectures';
            if (!file_exists(public_path($directory)))
                mkdir(public_path($directory), 0755, true);
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;
        } else {
            $path = "Images/Lectures/default.png";
        }

        // Handle videos
        $filePath360 = $filePath720 = $filePath1080 = null;

        if ($request->hasFile('lecture_file_360')) {
            $file360 = $request->file('lecture_file_360');
            $fileName360 = time() . '_360_' . $file360->getClientOriginalName();
            $file360->move(public_path('Files/360'), $fileName360);
            $filePath360 = 'Files/360/' . $fileName360;
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($filePath360);
            $duration = $fileInfo['playtime_seconds'];
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

        // Handle PDF
        $filePathPdf = null;
        if ($request->hasFile('lecture_file_pdf')) {
            $pdfDir = public_path('Files/PDFs');
            if (!file_exists($pdfDir))
                mkdir($pdfDir, 0777, true);
            $pdf = $request->file('lecture_file_pdf');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $filePathPdf = 'Files/PDFs/' . $pdfName;

            $parser = new \Smalot\PdfParser\Parser();
            $pdfObj = $parser->parseFile(public_path($filePathPdf));
            $pages = count($pdfObj->getPages());
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

        Quiz::create(['lecture_id' => $lecture->id]);
        $data = ['element' => 'lecture', 'id' => $lecture->id, 'name' => $lecture->name];
        session(['add_info' => $data]);
        session(['link' => '/lectures']);

        return redirect()->route('add.confirmation');

    }
}