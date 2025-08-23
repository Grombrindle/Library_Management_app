@props(['user' => App\Models\User::findOrFail(session('user'))])

<x-layout>
    <x-breadcrumb :links="['Home' => url('/welcome'), 'Users' => url('/users'), $user->userName => Request::url()]" :align=true />
    <x-infocard :editLink="'user/edit/' . $user->id" :deleteLink=null :object=$user objectType="User"
        name="{{ $user->userName }}">
        ● {{ __('messages.userName') }}: {{ $user->userName }}<br>
        ● {{ __('messages.userNumber') }}: <br> {{ $user->countryCode }} {{ $user->number }}<br>
        ● {{ __('messages.subjectsSubscribedTo') }}:
        @if ($user->subjects->count() == 0)
            <div style="color:black">none</div>
        @else
            <div>
                @if ($user->subjects->count() != 1)
                    [
                @endif
                @foreach ($user->subjects as $subject)
                    <span>{{ $subject->name }}</span>
                    @if (!$loop->last)
                        -
                    @endif
                @endforeach
                @if ($user->subjects->count() != 1)
                    ]
                @endif
            </div>
        @endif
        ● {{ __('messages.lecturesSubTo') }}:

        @if ($user->lectures->count() == 0)
        0
        @else
        <span>{{ $user->lectures->count() }}</span>
            @endif
    </x-infocard>

</x-layout>
