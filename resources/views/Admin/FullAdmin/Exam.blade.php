@props(['exam' => App\Models\Exam::findOrFail(session('exam'))])

<x-layout>
    <x-breadcrumb :links="array_merge(
        [__('messages.home') => url('/welcome'), __('messages.exams') => url('/exams')],
        [
            $exam->title => Request::url(),
        ],
    )" />
    <x-infocard :editLink="'exam/edit/' . $exam->id" deleteLink="deleteexam/{{ $exam->id }}" :object=$exam objectType="Exam"
        image="{{ asset($exam->thumbnailUrl) }}" name="{{ $exam->title }}" :file=true>
        <br>
        ● {{ __('messages.examTitle') }}: {{ $exam->title }}<br>
        ● {{ __('messages.examDescription') }}: {{ $exam->description }}<br>
        ● {{ __('messages.subject') }}: <a href="/subject/{{ $exam->subject_id }}"
            style="color:blue">{{ $exam->subject->name }}</a><br>
        ● {{ __('messages.pages') }}: {{ $exam->pages }}<br>
        ● {{ __('messages.examDate') }}: {{ $exam->date->format('Y-m-d') }}<br>
    </x-infocard>
</x-layout>
