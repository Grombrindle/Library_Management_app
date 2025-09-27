<?php

namespace App\Http\Controllers;

use App\Actions\Images\FetchTeacherImageAction;
use App\Actions\Images\FetchLectureImageAction;
use App\Actions\Images\FetchSubjectImageAction;
use App\Actions\Images\FetchCourseImageAction;
use App\Actions\Images\FetchResourceImageAction;
use App\Actions\Images\FetchExamImageAction;

class ImageController extends Controller
{
    protected FetchTeacherImageAction $fetchTeacherImage;
    protected FetchLectureImageAction $fetchLectureImage;
    protected FetchSubjectImageAction $fetchSubjectImage;
    protected FetchCourseImageAction $fetchCourseImage;
    protected FetchResourceImageAction $fetchResourceImage;
    protected FetchExamImageAction $fetchExamImage;

    public function __construct(
        FetchTeacherImageAction $fetchTeacherImage,
        FetchLectureImageAction $fetchLectureImage,
        FetchSubjectImageAction $fetchSubjectImage,
        FetchCourseImageAction $fetchCourseImage,
        FetchResourceImageAction $fetchResourceImage,
        FetchExamImageAction $fetchExamImage
    ) {
        $this->fetchTeacherImage = $fetchTeacherImage;
        $this->fetchLectureImage = $fetchLectureImage;
        $this->fetchSubjectImage = $fetchSubjectImage;
        $this->fetchCourseImage = $fetchCourseImage;
        $this->fetchResourceImage = $fetchResourceImage;
        $this->fetchExamImage = $fetchExamImage;
    }

    public function fetchTeacher($id)
    {
        return $this->handleImageResponse($this->fetchTeacherImage->execute($id));
    }

    public function fetchLecture($id)
    {
        return $this->handleImageResponse($this->fetchLectureImage->execute($id));
    }

    public function fetchSubject($id)
    {
        return $this->handleImageResponse($this->fetchSubjectImage->execute($id));
    }

    public function fetchCourse($id)
    {
        return $this->handleImageResponse($this->fetchCourseImage->execute($id));
    }

    public function fetchResource($id)
    {
        return $this->handleImageResponse($this->fetchResourceImage->execute($id));
    }

    public function fetchExam($id)
    {
        return $this->handleImageResponse($this->fetchExamImage->execute($id));
    }

    /**
     * Handle the standardized image response from actions
     */
    private function handleImageResponse(array $result)
    {
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'reason' => $result['reason'],
            ], $result['status']);
        }

        $mimeType = mime_content_type($result['filePath']);
        return response()->file($result['filePath'], ['Content-Type' => $mimeType]);
    }
}