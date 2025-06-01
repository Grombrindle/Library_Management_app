@props(['user' => App\Models\User::findOrFail(session('user'))])


<x-layout>

    @php
        $assignedObjects = $user->courses->pluck('id')->toArray();
    @endphp

    <x-editcard :selectedSubjects="$assignedObjects" link="edituser/{{ session('user') }}" relations="true" :subjects="$user->courses" object="User"
        :model="$user" menu="Course" :menuModel="App\Models\Course::all()" :lectures=true :isBanned="$user->isBanned">
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="user_name" style="margin-bottom:10%;">
                    {{ __('messages.userName') }}
                </label>
                <input type="text" name="user_name" id="user_name" value="{{ $user->userName }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;margin-bottom:10%;">
            </div>
            @error('user_name')
                <div class="error">{{ $message }}</div>
            @enderror
            
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="user_number">
                    {{ __('messages.userNumber') }}
                </label>
                <div style="position:relative; width: fit-content; height:fit-content; margin-bottom:10%; direction: ltr;">
                    <input type="text" name="user_number" id="user_number" placeholder="9XXXXXXXX"
                        value="{{ $user->number }}" autocomplete="off" inputmode="numeric"
                        style="height: 20%; text-align: left; font-size: 40%;text-indent:30%; width: 100%; box-sizing: border-box; @error('user_number') border:2px solid red @enderror; direction: ltr;"
                        oninput="if (this.value.length > 9) this.value = this.value.slice(0, 9); this.value = this.value.replace(/(?!^)\+/g,'').replace(/[^0-9+]/g, '')"
                        pattern="[0-9]{9}" required>
                    <span
                        style="position: absolute; left: 3px; top: 60%; transform: translateY(-50%); font-size: 50%; color: black; pointer-events: none; direction: ltr;">+963</span>
                    <div
                        style="position: absolute; left: 40px; top: 42%; height: 36%; width: 1px; background-color: black;">
                    </div>
                </div>
            </div>

            @error('user_number')
                <div class="error">{{ $message }}</div>
            @enderror
            <div
                style="margin-top: 20px; display: flex; align-items: center; flex-direction:column; justify-content: space-between; margin-left:auto; margin-right:auto; width:fit-content">
                <div>
                    <label for="isBanned" style="font-weight: bold;">
                        {{ __('messages.userStatus') }}
                    </label>
                    <span style="margin-left: 10px;">
                        {{ $user->isBanned ? __('messages.banned') : __('messages.active') }}
                    </span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="isBanned" id="isBanned" {{ $user->isBanned ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>
            <div style="background-color: black; width:100%; height:1px; margin-top:5%; margin-bottom:5%;"></div>
            <div>
                <div style="margin-bottom:3%;">
                    <strong>{{ __('messages.subscriptions') }}</strong>
                </div>

                <br>
                <label for="selected_objects_input">
                    {{ __('messages.courses') }}<br>
                    ({{ __('messages.clickToRemoveAndReAdd') }})
                </label>
                <br>
            </div>

    </x-editcard>
    </div>
</x-layout>
