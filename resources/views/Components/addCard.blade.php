@props([
    'link' => '#',
    'object' => null,
    'image' => null,
])

<style>
    .input-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 50%;
        grid-row-gap: 10%;
    }

    .icon {
        width: 80%;
        /* Adjust the size of the SVG icon */
        height: 80%;
        /* Adjust the size of the SVG icon */
        cursor: pointer;
        /* Optional: Add a pointer cursor for interactivity */
        transition: transform 0.3s ease;
    }

    .icon:hover {
        transform: scale(1.1);
        /* Slightly enlarge the icon on hover */
        transition: transform 0.3s ease;
        /* Smooth transition */
    }

    .ObjectContainer {
        width: 40rem;
        height: auto;
        display: flex;
        flex-direction: column;
        border: black 5px solid;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin-bottom: 0;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .textContainer {
        line-height: 50px;
        z-index: 2;
        font-size: 30px;
        text-align: center;
    }

    .submit-button {
        margin-top: 20px;
        margin-right: auto;
        margin-left: auto;
        padding: 10px 20px;
        font-size: 18px;
        background: #9997BC;
        border: #555184 3px solid;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-button:hover:not(:disabled) {
        background: white;
        color: black;
        animation: pulse 1s infinite;
    }

    .submit-button:disabled,
    .submit-button:disabled:hover {
        background-color: white;
        color: darkgray;
        border-color: darkgray;
        cursor: not-allowed;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 40, 40, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(40, 40, 40, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(40, 40, 40, 0);
        }
    }

    .image {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>



<div class="ObjectContainer">
    <form action="/{{ $link }}" method="POST" style="display:flex;flex-direction:column"
        enctype="multipart/form-data">
        @csrf
        <div style="width:50%; height:10%; margin-left:auto; margin-right:auto">
            @if ($object == 'Teacher')
                <img src="{{ asset('Images/Admins/teacherDefault.png') }}" alt="" id="image_preview"
                    class="image"accept="image/*" onchange="validateImageSize(this)">
            @elseif ($object == 'Admin')
                <img src="{{ asset('Images/Admins/adminDefault.png') }}" alt="" id="image_preview"
                    class="image"accept="image/*" onchange="validateImageSize(this)">
            @elseif ($object == 'Course')
                <img src="{{ asset('Images/Courses/default.png') }}" alt="" id="image_preview" class="image"
                    accept="image/*" onchange="validateImageSize(this)">
            @else
                <img src="{{ asset('Images/'.$object . 's/default.png') }}" alt="" id="image_preview"
                    class="image"accept="image/*" onchange="validateImageSize(this)">
            @endif
        </div>
        <br>
        <div
            style="display:flex; flex-direction:column; align-items:center; margin-top:5%;margin-bottom:5%; font-size:2rem;">
            <label for="object_image">
                
            @if ($object == 'Teacher')
                    {{__('messages.teacherImage')}}
                @elseif($object == 'Admin')
                    {{__('messages.adminImage')}}
                @elseif ($object == 'Course')
                    {{__('messages.courseImage')}}
                    @elseif ($object == 'Lecture')
                    {{__('messages.lectureImage')}}
                @elseif ($object == 'Subject')
                    {{__('messages.subjectImage')}}
                @endif
            </label>

            <input type="file" name="object_image" id="object_image"
                placeholder="Enter the image of the {{ Str::lower($object) }}" accept="image/*"
                onchange="validateImageSize(this)">
            <label for="object_image" style="color:#333333; font-size:2rem; text-align:center">{{__('messages.imageSizeWarning')}}</label>
        </div>
        @error('object_image')
            <div class="error">{{ $message }}</div>
        @enderror
        <br>
        <div class="textContainer">
            {{ $slot }}
        </div>

        <button type="submit" class="submit-button">
                @if ($object == 'Teacher')
                    {{__('messages.addTeacher')}}
                @elseif($object == 'Admin')
                    {{__('messages.addAdmin')}}
                @elseif ($object == 'User')
                    {{__('messages.addUser')}}
                @elseif ($object == 'Course')
                    {{__('messages.addCourse')}}
                    @elseif ($object == 'Lecture')
                    {{__('messages.addLecture')}}
                @elseif ($object == 'Subject')
                    {{__('messages.addSubject')}}
                @endif
            </button>
    </form>
    <script>
        function validateImageSize(input) {
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size;
                if (fileSize > maxSize) {
                    alert('Image size must be less than 2MB.');
                    input.value = ''; // Clear the file input
                }
            }
        }
    </script>
    @php

        $model = 'App\Models\\' . $object;
        $imagePath = asset($object . 's/default.png');

        if ($object == 'Admin' || $object == 'Teacher') {
            $imagePath = asset($object . 's/' . $object . 'Default.png');
        }
    @endphp
    <script>
        const imageInput = document.getElementById('object_image');
        const imagePreview = document.getElementById('image_preview');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result; // Update preview with selected image
                };
                reader.readAsDataURL(file);
            } else {
                // Reset to the original image if no file is selected

                imagePreview.src =
                    $imagePath;
            }
        });
    </script>
