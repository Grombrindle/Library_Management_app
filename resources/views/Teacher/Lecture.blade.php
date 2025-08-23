@props(['lecture' => App\Models\Lecture::findOrFail(session('lecture')), 'subject' => false])

<x-layout>
    <x-breadcrumb :links="array_merge([__('messages.home')=>url('/welcome'), __('messages.yourLectures') =>url('/lectures')], [
        $lecture->name => Request::url(),
    ])" align=true />
    <x-infocard :editLink="'lecture/edit/' . $lecture->id" deleteLink="deletelecture/{{ $lecture->id }}" :object=$lecture
        objectType="Lecture" image="{{ asset($lecture->image) }}" name="{{ $lecture->name }}" :file=true>
        ● {{ __('messages.lectureName') }}: {{ $lecture->name }}<br>
        {{-- {{ __('messages.lectureDescription') }}: {{ $lecture->description }}<br> --}}
        ● {{ __('messages.subject') }}: <a href="/subject/{{ $lecture->subject_id }}" style="color:blue">{{ App\Models\Subject::findOrFail($lecture->subject_id)->name }}</a>

    </x-infocard>

</x-layout>
