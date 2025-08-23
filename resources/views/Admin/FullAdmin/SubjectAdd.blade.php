<x-layout>
    <x-addcard : link="addsubject" object="Subject">
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="subject_name">
                {{ __('messages.subjectName') }}:
            </label>
            <input type="text" name="subject_name" id="subject_name" value="{{ old('subject_name') }}"
                autocomplete="off" style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        @error('subject_name')
            <div class="error">{{ $message }}</div>
        @enderror

        <div style="margin-top: 20px; display: flex; align-items: center; flex-direction:column; justify-content: space-between; margin-left:auto; margin-right:auto; width:fit-content">
            <div>
                <label for="subject_type" style="font-weight: bold;">
                    {{ __('messages.subjectType') }}
                </label>
                <br>
                <span style="margin-left: 10px;">
                    {{ old('subject_type') ? __('messages.scientific') : __('messages.literary') }}
                </span>
            </div>
            <label class="switch">
                <input type="checkbox" name="subject_type" id="subject_type" {{ old('subject_type') ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>
    </x-addcard>
</x-layout>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
        background-color: #2196F3;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectTypeCheckbox = document.getElementById('subject_type');
        const typeSpan = subjectTypeCheckbox.parentElement.previousElementSibling.querySelector('span');
        
        subjectTypeCheckbox.addEventListener('change', function() {
            typeSpan.textContent = this.checked ? '{{ __("messages.scientific") }}' : '{{ __("messages.literary") }}';
        });
    });
</script>
