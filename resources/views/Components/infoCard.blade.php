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
])
<style>
    .ObjectContainer {
        padding: 1%;
        width: 40rem;
        height: 150%;
        display: flex;
        color: var(--text-color);
        flex-direction: column;
        border: black 5px solid;
        /* justify-content: center; */
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin-bottom: 3%;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 0 10px 0.5px var(--text-color);
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

    @media(max-width:1600px) {
        .textContainer {
            font-size: 25px;
        }
    }

    @media(max-width:800px) {
        .textContainer {
            font-size: 20px;
        }
    }

    @media(max-width:600px) {
        .textContainer {
            font-size: 15px;
        }
    }

    @media(max-width:400px) {
        .textContainer {
            font-size: 10px;
        }
    }

    .Object:hover {
        background-color: #9997BC;
        border: #555184 4px solid;
        border-radius: 10px;
        color: black;
    }

    .textContainer {
        line-height: 50px;
        z-index: 2;
        text-align: center;
    }

    .buttonContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 25px;
        width: fit-content;
    }

    .button {
        background-color: #555184;
        border: 0.15rem white solid;
        text-decoration: none;
        font-size: 20px;
        color: var(--text-color);
        text-align: center;
        font-family: 'Pridi';
        margin-bottom: 2rem;
        padding: 0.5rem 0.5rem;
        border-radius: 7.5%;
        transition: 0.5s ease;
        height: fit-content;
        width: fit-content;

    }

    .button:hover {

        background-color: #9997BC;
        border: 0.15rem solid #555184;
        color: #555184;
    }

    .button:disabled {
        background-color: white;
        border-color: darkgray;
        color: darkgray;
        cursor: not-allowed;
        margin-bottom: 2rem;
        height: fit-content;
        width: fit-content;
    }

    .deleteButton {
        background-color: red;
        border: 0.15rem white solid;
        text-decoration: none;
        font-size: 20px;
        color: var(--text-color);
        text-align: center;
        height: fit-content;
        width: fit-content;
        font-family: 'Pridi';
        margin-bottom: 2rem;
        padding: 0.5rem 0.5rem;
        border-radius: 7.5%;
        transition: 0.5s ease;
        cursor: pointer;
    }

    .deleteButton:hover {
        border-color: red;
        background-color: black;
        color: red;
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
        </div>
    @endif
    @if ($addLecture != null)
        <a href="addlecture/{{ $object->id }}" class="button" style="background-color:#9997BC">{{__('messages.addLecture')}}</a>
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