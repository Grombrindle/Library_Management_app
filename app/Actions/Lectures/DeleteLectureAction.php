<?php

namespace App\Actions\Lectures;

use App\Models\Lecture;
use App\Models\Course;
use Illuminate\Http\Request;

class DeleteLectureAction
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

    public function execute($id)
    {
        $lecture = Lecture::findOrFail($id);
        $name = $lecture->name;

        $course = Course::find($lecture->course_id);
        $course->decrement('lecturesCount');
        $course->save();

        if ($lecture->image != "Images/Lectures/default.png" && file_exists(public_path($lecture->image))) {
            unlink(public_path($lecture->image));
        }

        if ($lecture->file_360 && file_exists(public_path($lecture->file_360)))
            unlink(public_path($lecture->file_360));
        if ($lecture->file_720 && file_exists(public_path($lecture->file_720)))
            unlink(public_path($lecture->file_720));
        if ($lecture->file_1080 && file_exists(public_path($lecture->file_1080)))
            unlink(public_path($lecture->file_1080));
        if ($lecture->file_pdf && file_exists(public_path($lecture->file_pdf)))
            unlink(public_path($lecture->file_pdf));

        $lecture->delete();


        $data = ['element' => 'lecture', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/lectures']);

        return redirect()->route('delete.confirmation');
    }
}