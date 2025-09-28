<?php

namespace App\Services\Lectures;

use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\Course;
use getID3;
use Illuminate\Http\Request;

use App\Actions\Lectures\GetLectureRatingsAction;
use App\Actions\Lectures\GetCourseLecturesAction;
use App\Actions\Lectures\GetCourseLecturesRatedAction;
use App\Actions\Lectures\GetCourseLecturesRecentAction;
use App\Actions\Lectures\FetchLectureQuizQuestionsAction;
use App\Actions\Lectures\RateLectureAction;
use App\Actions\Lectures\IncrementLectureViewsAction;
use App\Actions\Lectures\FetchLectureFileAction;
use App\Actions\Lectures\FetchLectureAction;

class LectureService
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


    public function __construct(
        private GetLectureRatingsAction $getLectureRatingsAction,
        private GetCourseLecturesAction $getCourseLecturesAction,
        private GetCourseLecturesRatedAction $getCourseLecturesRatedAction,
        private GetCourseLecturesRecentAction $getCourseLecturesRecentAction,
        private FetchLectureQuizQuestionsAction $fetchQuizQuestionsAction,
        private RateLectureAction $rateLectureAction,
        private IncrementLectureViewsAction $incrementViewsAction,
        private FetchLectureFileAction $fetchLectureFileAction,
        private FetchLectureAction $fetchLectureAction,
    ) {
    }

    public function getLectureRatings(int $lectureId): array
    {
        return $this->getLectureRatingsAction->execute($lectureId);
    }

    public function getCourseLectures(int $courseId): array
    {
        return $this->getCourseLecturesAction->execute($courseId);
    }

    public function getCourseLecturesRated(int $courseId): array
    {
        return $this->getCourseLecturesRatedAction->execute($courseId);
    }

    public function getCourseLecturesRecent(int $courseId): array
    {
        return $this->getCourseLecturesRecentAction->execute($courseId);
    }

    public function fetchQuizQuestions(int $lectureId): array
    {
        return $this->fetchQuizQuestionsAction->execute($lectureId);
    }

    public function rateLecture(int $lectureId, int $rating, ?string $review = null): array
    {
        return $this->rateLectureAction->execute($lectureId, $rating, $review);
    }

    public function incrementViews(int $lectureId): array
    {
        return $this->incrementViewsAction->execute($lectureId);
    }

    public function fetchLectureFile(int $lectureId, string $fileType): array
    {
        return $this->fetchLectureFileAction->execute($lectureId, $fileType);
    }

    public function fetchLecture(int $lectureId): array
    {
        return $this->fetchLectureAction->execute($lectureId);
    }
    public function addLecture(Request $request)
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

    public function editLecture(Request $request, $id)
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
            $lecture->duration = $request->input('duration');
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
            $lecture->duration = $request->input('duration');
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
            $lecture->duration = $request->input('duration');
            $lecture->pages = null;
        }

        $lecture->save();

        $data = ['element' => 'lecture', 'id' => $lecture->id, 'name' => $lecture->name];
        session(['update_info' => $data]);
        session(['link' => '/lectures']);

        return redirect()->route('update.confirmation');
    }

    public function deleteLecture($id)
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