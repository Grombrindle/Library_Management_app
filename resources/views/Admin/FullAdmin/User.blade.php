@props(['user' => App\Models\User::findOrFail(session('user'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.users') => url('/users'), $user->userName => Request::url()]" :align=true />
    <x-infocard :editLink="'user/edit/' . $user->id" deleteLink="deleteuser/{{ $user->id }}" :object=$user objectType="User"
        name="{{ $user->userName }}">
        ● {{ __('messages.userName') }}: {{ $user->userName }}<br>
        ● {{ __('messages.userNumber') }}: {{ $user->countryCode }} {{ $user->number }}<br>
        ● {{ __('messages.courseSubscribedTo') }}:
        @if ($user->courses->count() == 0)
            <div style="color:black">{{ __('messages.none') }}</div>
        @else
            <div>
                @if ($user->courses->count() != 1)
                    [
                @endif
                @foreach ($user->courses as $course)
                    <a href="/course/{{ $course->id }}" style="color:blue">{{ $course->name }}</a>
                    @if (!$loop->last)
                        -
                    @endif
                @endforeach
                @if ($user->courses->count() != 1)
                    ]
                @endif
            </div>
        @endif
        ● {{ __('messages.numberOfLecturesSubscribedTo') }}:

        @if ($user->lectures->count() == 0)
        0
        @else
        <a href="/user/{{$user->id}}/lectures" style="color:blue">{{ $user->lectures->count() }}</a>
            @endif

            @if ($user->isBanned)
                <div style="color: red; font-weight: bold; margin-top: 1rem; font-size:60px;">{{ __('messages.banned') }}</div>
            @endif
    </x-infocard>

</x-layout>
