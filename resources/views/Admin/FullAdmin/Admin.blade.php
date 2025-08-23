@props(['admin' => App\Models\Admin::findOrFail(session('admin'))])

<x-layout>
    <x-breadcrumb :links="array_merge(session('breadcrumb_admins', [__('messages.home') => url('/welcome')]), [__('messages.admins') =>url('/admins')], [
        $admin->name => Request::url(),
    ])" align=true style="align-self:flex-start"/>
    <x-infocard :editLink="'admin/edit/' . $admin->id" deleteLink="deleteadmin/{{ $admin->id }}" :object=$admin id="{{$admin->id}}"
        objectType="Admin" privileges="{{ $admin->privileges }}" image="{{ asset($admin->image) }}"
        name="{{ $admin->name }}">
        ● {{ __('messages.adminName') }}: {{ $admin->name }}<br>
        ● {{ __('messages.adminUserName') }}: {{ $admin->userName }}<br>
        ● {{ __('messages.adminNumber') }}: {{$admin->countryCode}} {{ $admin->number }}<br>
        ● {{ __('messages.privileges') }}:
        @if ($admin->privileges == 0)
            <a href="/teacher/{{ $admin->teacher_id }}" style="color:blue">{{ __('messages.teacher') }}</a>
        @elseif ($admin->privileges == 1)
            {{ __('messages.semiAdmin') }}
        @else
            {{ __('messages.admin') }}
        @endif

    </x-infocard>

</x-layout>
