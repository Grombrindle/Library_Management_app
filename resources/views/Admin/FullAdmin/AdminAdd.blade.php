<x-layout>
    <x-addcard : link="addadmin" object="Admin">

        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="admin_name">
                {{ __('messages.adminName') }}:
            </label>
            <input type="text" name="admin_name" id="admin_name" value="{{old('admin_name')}}" autocomplete="off"
                style="height:20%; text-align:center; font-size:40%; width:fit-content; @error('admin_name') border:2px solid red @enderror" required>
        </div>
        @error('admin_name')
            <div class="error">{{ $message }}</div>
        @enderror

        <br>

        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="admin_user_name">
                {{ __('messages.userName') }}:
            </label>
            <input type="text" name="admin_user_name" id="admin_user_name" value="{{old('admin_user_name')}}" autocomplete="off"
                style="height:20%; text-align:center; font-size:40%; width:fit-content; @error('admin_user_name') border:2px solid red @enderror" required>
        </div>
        @error('admin_user_name')
            <div class="error">{{ $message }}</div>
        @enderror

        <br>

        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="admin_number">
                {{ __('messages.adminNumber') }}:
            </label>
            <div style="position: relative; width: fit-content; height: fit-content; line-height: 0; direction: ltr;">
                <input type="text" name="admin_number" id="admin_number" placeholder="9XXXXXXXX" autocomplete="off"
                    inputmode="numeric" value="{{old('admin_number')}}"
                    style="height: 20%; text-align: left; font-size: 40%; text-indent:30%; width: 100%; box-sizing: border-box; @error('admin_number') border:2px solid red @enderror; vertical-align: top; margin: 0; padding: 0; direction: ltr;"
                    oninput="if (this.value.length > 9) this.value = this.value.slice(0, 9); this.value = this.value.replace(/(?!^)\+/g,'').replace(/[^0-9+]/g, '')"
                    pattern="[0-9]{9}" required>
                <span
                    style="position: absolute; left: 4.5px; top: 50%; transform: translateY(-50%); font-size: 50%; color: black; pointer-events: none; direction: ltr;">+963</span>
                <div
                    style="position: absolute; left: 40px; top: 0%; height: 100%; width: 1px; background-color: black;">
                </div>
            </div>
        </div>
        @error('admin_number')
            <div class="error">{{ $message }}</div>
        @enderror

        <br>

        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="admin_password">
                {{ __('messages.adminPassword') }}:
            </label>
            <input type="password" name="admin_password" id="admin_password" value=""
                style="height:20%; text-align:center; font-size:40%; width:fit-content;" minlength="8" required>
        </div>
        <br>

        <div class="dropdown-container">
            <label for="admin-dropdown" style="font-size: 30px;">{{ __('messages.privileges') }}:</label>
            <select id="admin-dropdown" name="admin_privileges" class="dropdown" required>
                <option value="" {{ old('admin_privileges') == '' ? 'selected' : '' }}>{{ __('messages.selectPrivileges') }}</option>
                <option value="Admin" {{ old('admin_privileges') == 'Admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                <option value="Semi-Admin" {{ old('admin_privileges') == 'Semi-Admin' ? 'selected' : '' }}>{{ __('messages.semiAdmin') }}</option>
            </select>
        </div>

    </x-addcard>
</x-layout>
