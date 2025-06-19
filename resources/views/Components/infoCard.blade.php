@props([
    'editLink' => null,
    'deleteLink' => null,
    'editLecturesLink' => null,
    'editSubscriptionsLink' => null,
    'lecturesCount' => null,
    'subscriptionsCount' => null,
    'object',
    'objectType',
    'image' => null,
    'name',
    'warning' => null,
    'privileges' => null,
    'file' => null,
    'addLecture' => null,
    'addCourse' => null,
])
<style>
    .ObjectContainer {
        padding: 2rem;
        width: 40rem;
        max-width: 95vw;
        height: auto;
        display: flex;
        color: var(--text-color);
        flex-direction: column;
        border: black 5px solid;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin: 0 auto 2rem;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 5px 4px 0.5px var(--text-color);
        transition: all 0.3s ease;
    }

    .Object {
        background: #555184;
        padding: 5px 0;
        margin-top: 2%;
        font-size: 20px;
        border: #9997BC 4px solid;
        color: white;
        border-radius: 3px;
        display: flex;
        flex-direction: row;
        transition: 0.3s ease;
    }

    .Object:hover {
        background-color: #9997BC;
        border: #555184 4px solid;
        border-radius: 10px;
        color: black;
    }

    .textContainer {
        line-height: 1.5;
        z-index: 2;
        text-align: center;
        font-size: 1.5rem;
        padding: 0 1rem;
        word-break: break-word;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 1.5rem;
    }

    .buttonContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        width: 100%;
        margin-top: 1rem;
    }

    .button, .deleteButton {
        background-color: #555184;
        border: 0.15rem white solid;
        text-decoration: none;
        font-size: 1.1rem;
        color: var(--text-color);
        text-align: center;
        font-family: 'Pridi', sans-serif;
        margin-bottom: 0.5rem;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: 0.3s ease;
        height: fit-content;
        width: fit-content;
        cursor: pointer;
        outline: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        -webkit-tap-highlight-color: transparent;
    }
    .button:focus, .deleteButton:focus {
        border-color: #9997BC;
        box-shadow: 0 0 0 2px #9997BC33;
    }
    .button:hover:not(:disabled), .deleteButton:hover:not(:disabled) {
        background-color: #9997BC;
        border: 0.15rem solid #555184;
        color: #555184;
    }
    .button:disabled, .deleteButton:disabled {
        background-color: #eee;
        border-color: darkgray;
        color: darkgray;
        cursor: not-allowed;
    }
    .deleteButton {
        background-color: #e74c3c;
        border: 0.15rem white solid;
        color: #fff;
    }
    .deleteButton:hover:not(:disabled) {
        border-color: #e74c3c;
        background-color: #222;
        color: #e74c3c;
    }
    /* Responsive Design */
    @media (max-width: 1200px) {
        .ObjectContainer {
            width: 32rem;
        }
        .textContainer {
            font-size: 1.25rem;
        }
    }
    @media (max-width: 992px) {
        .ObjectContainer {
            width: 26rem;
        }
        .textContainer {
            font-size: 1.1rem;
        }
    }
    @media (max-width: 768px) {
        .ObjectContainer {
            width: 98vw;
            padding: 2% 1%;
        }
        .textContainer {
            font-size: 1rem;
        }
        .button, .deleteButton {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    }
    @media (max-width: 576px) {
        .ObjectContainer {
            width: 100vw;
            border-width: 3px;
            padding: 2% 0.5%;
        }
        .textContainer {
            font-size: 0.95rem;
        }
        .button, .deleteButton {
            font-size: 0.95rem;
            padding: 0.5rem 0.75rem;
        }
    }
    @media (max-width: 400px) {
        .ObjectContainer {
            width: 100vw;
            border-width: 2px;
            padding: 1% 0.25%;
        }
        .textContainer {
            font-size: 1.5rem;
        }
        .button, .deleteButton {
            font-size: 1.8rem;
            padding: 0.4rem 0.5rem;
        }
    }
    /* Touch device optimizations */
    @media (hover: none) {
        .button:hover, .deleteButton:hover {
            background-color: #9997BC;
            color: #fff;
            box-shadow: none;
            transform: none;
        }
        .button:active, .deleteButton:active {
            background-color: #555184;
            color: #fff;
            transform: scale(0.98);
        }
    }
</style>


<div class="ObjectContainer">
    @if ($image != null)
        <img src="{{ $image }}" alt="{{ $objectType }} Image"
            style="width: 100px; aspect-ratio: 1/1; object-fit:scale-down; border-radius: 8px;">
    @endif
    <div class="textContainer">
        {{ $slot }}
    </div>
</div>

<div class="buttonContainer">
    @if ($editLink != null)
        <div style="">

            <a href="/{{ $editLink }}" class="button">
                @if ($objectType == 'Teacher')
                    {{__('messages.editTeacher')}}
                @elseif($objectType == 'Admin')
                    {{__('messages.editAdmin')}}
                @elseif ($objectType == 'User')
                    {{__('messages.editUser')}}
                @elseif ($objectType == 'Course')
                    {{__('messages.editCourse')}}
                    @elseif ($objectType == 'Lecture')
                    {{__('messages.editLecture')}}
                @elseif ($objectType == 'Subject')
                    {{__('messages.editSubject')}}
                @endif
            </a>
        </div>
    @endif
    @if ($file != null)
        <div style="height:fit-content;">
            @if ($object->file_pdf != null)
                <a href="show/{{ $object->id }}/pdf" target="_blank" class="button" style="background-color:#9997BC">{{__('messages.showLecture')}} PDF</a>
            @else
                @if ($object->file_360 != null)
                    <a href="show/{{ $object->id }}/360" target="_blank" class="button" style="background-color:#9997BC">{{__('messages.showLecture')}} 360p</a>
                @else
                    <button class="button" disabled>{{__('messages.showLecture')}} 360p</button>
                @endif
                @if ($object->file_720 != null)
                    <a href="show/{{ $object->id }}/720" target="_blank" class="button" style="background-color:#9997BC">{{__('messages.showLecture')}} 720p</a>
                @else
                    <button class="button" disabled>{{__('messages.showLecture')}} 720p</button>
                @endif
                @if ($object->file_1080 != null)
                    <a href="show/{{ $object->id }}/1080" target="_blank" class="button"
                    style="background-color:#9997BC; margin-left:auto;margin-right:auto;">{{__('messages.showLecture')}} 1080p</a>
            @else
                <button class="button" style="margin-left:auto;margin-right:auto;" disabled>{{__('messages.showLecture')}} 1080p</button>
                @endif
            @endif
        </div>
    @endif
    @if ($addLecture != null)
        <a href="addlecture/{{ $object->id }}" class="button" style="background-color:#9997BC">{{__('messages.addLecture')}}</a>
    @endif
    @if ($addCourse != null)
        <a href="addcourse/{{ $object->id }}" class="button" style="background-color:#9997BC">{{__('messages.addCourse')}}</a>
    @endif
    {{-- <div style="margin-bottom:5%;">
        @if ($lecturesCount != null)
        @if ($lecturesCount > 0)
        <a href="/{{ $editLecturesLink }}" class="button">Show Lectures</a>
        @else
        <button class="button" disabled>Show Lectures</button>
        @endif
        @endif
        @if ($subscriptionsCount != null)
        @if ($subscriptionsCount > 0)
        <a href="/{{ $editSubscriptionsLink }}" class="button">Show Subscriptions</a>
        @else
        <button class="button" disabled>Show Subscriptions</button>
        @endif
        @endif
    </div> --}}
    <form action="/{{ $deleteLink }}" method="POST"
        onsubmit="return handleDelete(event, {{ Auth::id() == $object->id && $objectType == 'Admin' ? 'true' : 'false' }}, '{{ $name }}', '{{ $warning }}');">
        @csrf
        @method('DELETE')
        @if ($deleteLink != null)
            <button class="deleteButton" style="">
                @if ($objectType == 'Teacher')
                    {{__('messages.deleteTeacher')}}
                @elseif($objectType == 'Admin')
                    {{__('messages.deleteAdmin')}}
                @elseif ($objectType == 'User')
                    {{__('messages.deleteUser')}}
                @elseif ($objectType == 'Course')
                    {{__('messages.deleteCourse')}}
                    @elseif ($objectType == 'Lecture')
                    {{__('messages.deleteLecture')}}
                @elseif ($objectType == 'Subject')
                    {{__('messages.deleteSubject')}}
                @endif
            </button>
        @endif
    </form>
</div>
<script>
    function handleDelete(event, isCurrentAdmin, name, warning) {
        if (isCurrentAdmin) {
            preventDelete();
            return false; // Prevent form submission
        } else {
            return confirmDelete(name, warning);
        }
    }

    function confirmDelete(name, warning) {
        return confirm('{{ __("messages.confirmDeleteItem", ["name" => $name, "warning" => $warning]) }}');
    }

    function preventDelete() {
        alert('{{ __("messages.cannotDeleteAccount") }}');    }
</script>