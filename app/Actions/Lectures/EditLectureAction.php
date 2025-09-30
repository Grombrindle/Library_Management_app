<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;
use Illuminate\Http\Request;

class EditLectureAction
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

    public function execute(Request $request, $id)
    {
        $lecture = Lecture::findOrFail($id);
        $lecture->name = $request->lecture_name;
        $lecture->description = $request->lecture_description;

        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $directory = 'Images/Lectures';
            if (!file_exists(public_path($directory)))
                mkdir(public_path($directory), 0755, true);
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($directory), $filename);
            $path = $directory . '/' . $filename;

            if ($lecture->image != "Images/Lectures/default.png" && file_exists(public_path($lecture->image))) {
                unlink(public_path($lecture->image));
            }

            $lecture->image = $path;
        }

        if ($request->hasFile('lecture_file_pdf')) {
            if ($lecture->file_pdf && file_exists(public_path($lecture->file_pdf)))
                unlink(public_path($lecture->file_pdf));
            $pdfDir = public_path('Files/PDFs');
            if (!file_exists($pdfDir))
                mkdir($pdfDir, 0777, true);
            $pdf = $request->file('lecture_file_pdf');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->move($pdfDir, $pdfName);
            $lecture->file_pdf = 'Files/PDFs/' . $pdfName;
            $lecture->type = 0;
            $lecture->pages = $request->input('pages');
            $lecture->duration = null;
        }

        if ($request->hasFile('lecture_file_360')) {
            if ($lecture->file_360 && file_exists(public_path($lecture->file_360)))
                unlink(public_path($lecture->file_360));
            $file360 = $request->file('lecture_file_360');
            $fileName360 = time() . '_360_' . $file360->getClientOriginalName();
            $file360->move(public_path('Files/360'), $fileName360);
            $lecture->file_360 = 'Files/360/' . $fileName360;
            $lecture->type = 1;
            $lecture->pages = null;
        }
        if ($request->hasFile('lecture_file_720')) {
            if ($lecture->file_720 && file_exists(public_path($lecture->file_720)))
                unlink(public_path($lecture->file_720));
            $file720 = $request->file('lecture_file_720');
            $fileName720 = time() . '_720_' . $file720->getClientOriginalName();
            $file720->move(public_path('Files/720'), $fileName720);
            $lecture->file_720 = 'Files/720/' . $fileName720;
            $lecture->type = 1;
            $lecture->pages = null;
        }
        if ($request->hasFile('lecture_file_1080')) {
            if ($lecture->file_1080 && file_exists(public_path($lecture->file_1080)))
                unlink(public_path($lecture->file_1080));
            $file1080 = $request->file('lecture_file_1080');
            $fileName1080 = time() . '_1080_' . $file1080->getClientOriginalName();
            $file1080->move(public_path('Files/1080'), $fileName1080);
            $lecture->file_1080 = 'Files/1080/' . $fileName1080;
            $lecture->type = 1;
            $lecture->pages = null;
        }

        $lecture->save();

        $data = ['element' => 'lecture', 'id' => $lecture->id, 'name' => $lecture->name];
        session(['update_info' => $data]);
        session(['link' => '/lectures']);

        return redirect()->route('update.confirmation');
    }
}