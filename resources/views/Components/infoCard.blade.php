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
    'request' => false,
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

    .quizContainer {
        text-align: left;
        font-size: 1.3rem;
    }

    .quiz-question-row {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.2rem;
    }

    .quiz-remove-btn {
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 2rem;
        height: 2rem;
        font-size: 1.3rem;
        cursor: pointer;
        margin-left: 0.5rem;
        transition: background 0.2s;
    }

    .quiz-remove-btn:hover {
        background: #c0392b;
    }

    .quiz-add-btn {
        background: #555184;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 2.2rem;
        height: 2.2rem;
        font-size: 1.5rem;
        cursor: pointer;
        margin: 1rem auto 0 auto;
        display: block;
        transition: background 0.2s;
    }

    .quiz-add-btn:hover {
        background: #9997BC;
        color: #555184;
    }

    .quiz-options-inputs {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        margin-left: 1.5rem;
        margin-top: 0.3rem;
    }

    .quiz-option-input {
        width: 90%;
        padding: 0.2rem 0.5rem;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 0.2rem;
    }

    .button,
    .deleteButton {
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        -webkit-tap-highlight-color: transparent;
    }

    .button:focus,
    .deleteButton:focus {
        border-color: #9997BC;
        box-shadow: 0 0 0 2px #9997BC33;
    }

    .button:hover:not(:disabled),
    .deleteButton:hover:not(:disabled) {
        background-color: #9997BC;
        border: 0.15rem solid #555184;
        color: #555184;
    }

    .button:disabled,
    .deleteButton:disabled {
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

        .button,
        .deleteButton {
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

        .button,
        .deleteButton {
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

        .button,
        .deleteButton {
            font-size: 1.8rem;
            padding: 0.4rem 0.5rem;
        }
    }

    /* Touch device optimizations */
    @media (hover: none) {

        .button:hover,
        .deleteButton:hover {
            background-color: #9997BC;
            color: #fff;
            box-shadow: none;
            transform: none;
        }

        .button:active,
        .deleteButton:active {
            background-color: #555184;
            color: #fff;
            transform: scale(0.98);
        }
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-box {
        background: white;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        max-width: 90vw;
        max-height: 80vh;
        overflow-y: auto;
        text-align: center;
        position: relative;
        opacity: 1;
        transform: translateY(100vh) scale(0.98);
        transition: transform 0.5s cubic-bezier(.23, 1.01, .32, 1), opacity 0.3s;
    }

    .modal-overlay.active .modal-box {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    @media (max-width: 600px) {
        .modal-box {
            padding: 1rem 0.5rem;
        }
    }
</style>


<div class="ObjectContainer" style="@if ($request) margin-top:10rem; @endif">
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
                    {{ __('messages.editTeacher') }}
                @elseif($objectType == 'Admin')
                    {{ __('messages.editAdmin') }}
                @elseif ($objectType == 'User')
                    {{ __('messages.editUser') }}
                @elseif ($objectType == 'Course')
                    {{ __('messages.editCourse') }}
                @elseif ($objectType == 'Exam')
                    {{ __('messages.editExam') }}
                @elseif ($objectType == 'Lecture')
                    {{ __('messages.editLecture') }}
                @elseif ($objectType == 'Subject')
                    {{ __('messages.editSubject') }}
                @elseif ($objectType == 'Resource')
                    {{ __('messages.editResource') }}
                @endif
            </a>
        </div>
    @endif
    @if ($file != null)
        <div style="height:fit-content;">
            @if ($objectType == 'Resource')
                <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                    @php
                        $pdfFiles = $object->pdf_files;
                        $languages = [
                            'ar' => __('messages.pdfArabic'),
                            'en' => __('messages.pdfEnglish'),
                            'es' => __('messages.pdfSpanish'),
                            'de' => __('messages.pdfGerman'),
                            'fr' => __('messages.pdfFrench'),
                        ];
                    @endphp

                    @foreach ($languages as $langCode => $langName)
                        @if (isset($pdfFiles[$langCode]) && $pdfFiles[$langCode])
                            <a href="show/{{ $object->id }}/pdf/{{ $langCode }}" target="_blank" class="button"
                                style="background-color:#9997BC; min-width: 120px;">
                                {{ $langName }}
                            </a>
                        @else
                            <button class="button" disabled style="min-width: 120px;">
                                {{ $langName }}
                            </button>
                        @endif
                    @endforeach
                </div>
                @if ($object->audio_file == null)
                    <button class="button" style="margin: 20px auto 0 auto; display: block;"
                        disabled>{{ __('messages.showResource') }} Audio</button>
                @else
                    <a href="show/{{ $object->id }}/audio" target="_blank" class="button"
                        style="background-color:#9997BC; margin: 20px auto 0 auto; display: block;">{{ __('messages.showResource') }}
                        Audio</a>
                @endif
            @elseif($objectType == 'Exam')
                <a href="show/{{ $object->id }}" target="_blank" class="button"
                    style="background-color:#9997BC">{{ __('messages.showExam') }} PDF</a>
            @else
                @if ($object->file_pdf != null)
                    <a href="show/{{ $object->id }}/pdf" target="_blank" class="button"
                        style="background-color:#9997BC">{{ __('messages.showLecture') }} PDF</a>
                @else
                    @if ($object->file_360 != null)
                        <a href="show/{{ $object->id }}/360" target="_blank" class="button"
                            style="background-color:#9997BC">{{ __('messages.showLecture') }} 360p</a>
                    @else
                        <button class="button" disabled>{{ __('messages.showLecture') }} 360p</button>
                    @endif
                    @if ($object->file_720 != null)
                        <a href="show/{{ $object->id }}/720" target="_blank" class="button"
                            style="background-color:#9997BC">{{ __('messages.showLecture') }} 720p</a>
                    @else
                        <button class="button" disabled>{{ __('messages.showLecture') }} 720p</button>
                    @endif
                    @if ($object->file_1080 != null)
                        <a href="show/{{ $object->id }}/1080" target="_blank" class="button"
                            style="background-color:#9997BC; margin-left:auto;margin-right:auto;">{{ __('messages.showLecture') }}
                            1080p</a>
                    @else
                        <button class="button" style="margin-left:auto;margin-right:auto;"
                            disabled>{{ __('messages.showLecture') }} 1080p</button>
                    @endif
                @endif
            @endif
        </div>
        @if ($objectType == 'Lecture')
            <button class="button" id="showQuizBtn" style="margin-top: 10px;">Show quiz</button>
        @endif
    @endif
    @if ($addLecture != null)
        <a href="addlecture/{{ $object->id }}" class="button"
            style="background-color:#9997BC">{{ __('messages.addLecture') }}</a>
    @endif
    @if ($addCourse != null)
        <a href="addcourse/{{ $object->id }}" class="button"
            style="background-color:#9997BC">{{ __('messages.addCourse') }}</a>
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
    @if ($deleteLink != null)
        <form action="/{{ $deleteLink }}" method="POST"
            onsubmit="return handleDelete(event, {{ Auth::id() == $object->id && $objectType == 'Admin' ? 'true' : 'false' }}, '{{ $name }}', '{{ $warning }}');">
            @csrf
            @method('DELETE')
            <button class="deleteButton" style="">
                @if ($objectType == 'Teacher')
                    {{ __('messages.deleteTeacher') }}
                @elseif($objectType == 'Admin')
                    {{ __('messages.deleteAdmin') }}
                @elseif ($objectType == 'User')
                    {{ __('messages.deleteUser') }}
                @elseif ($objectType == 'Course')
                    {{ __('messages.deleteCourse') }}
                @elseif ($objectType == 'Exam')
                    {{ __('messages.deleteExam') }}
                @elseif ($objectType == 'Lecture')
                    {{ __('messages.deleteLecture') }}
                @elseif ($objectType == 'Subject')
                    {{ __('messages.deleteSubject') }}
                @elseif ($objectType == 'Resource')
                    {{ __('messages.deleteResource') }}
                @endif
            </button>
    @endif
    </form>
</div>
@if ($objectType == 'Lecture')
    <div class="modal-overlay" id="quizModal">
        <div class="modal-box">
            <form id="quizEditForm" method="POST" action="/updatequiz/{{ $object->quiz->id }}">
                @csrf
                @method('PUT')
                <div class="quizContainer" id="quizContainer">
                </div>
                <input type="hidden" name="quiz_data" id="quizDataInput">
                <button type="button" class="quiz-add-btn" id="addQuestionBtn">+</button>
                <button type="submit" class="button" id="updateQuizBtn" style="margin-top:1rem;">Update Quiz</button>
            </form>
        </div>
    </div>
@endif
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
        return confirm('{{ __('messages.confirmDeleteItem', ['name' => $name ?? "Unknown", 'warning' => $warning]) }}');
    }

    function preventDelete() {
        alert('{{ __('messages.cannotDeleteAccount') }}');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const showQuizBtn = document.getElementById('showQuizBtn');
        const quizModal = document.getElementById('quizModal');
        const modalBox = quizModal ? quizModal.querySelector('.modal-box') : null;

        // Initial quiz data from PHP
        let quizData = [];
        let quizId = null;
        @php
            $quizArray = [];
            $quizId = isset($object->quiz) ? $object->quiz->id : null;
            if (isset($object->quiz) && isset($object->quiz->questions)) {
                foreach ($object->quiz->questions as $q) {
                    $quizArray[] = [
                        'questionText' => $q->questionText,
                        'options' => is_array($q->options) ? $q->options : json_decode($q->options, true),
                        'correctAnswerIndex' => $q->correctAnswerIndex,
                    ];
                }
            }
        @endphp
        quizData = @json($quizArray);
        quizId = @json($quizId);

        // Render quiz questions and options
        function renderQuiz() {
            const quizContainer = document.getElementById('quizContainer');
            quizContainer.innerHTML = '';
            quizData.forEach((q, idx) => {
                const row = document.createElement('div');
                row.className = 'quiz-question-row';
                row.innerHTML = `
                    <input type="text" class="quiz-question-input" value="${q.questionText.replace(/"/g, '&quot;')}" placeholder="Question text" style="flex:1;max-width:60%">
                    <button type="button" class="quiz-remove-btn" title="Remove question">-</button>
                `;
                // Remove button
                row.querySelector('.quiz-remove-btn').onclick = () => {
                    quizData.splice(idx, 1);
                    renderQuiz();
                };
                // Question text input
                row.querySelector('.quiz-question-input').oninput = (e) => {
                    quizData[idx].questionText = e.target.value;
                };
                // Options and correct answer radio
                const optionsDiv = document.createElement('div');
                optionsDiv.className = 'quiz-options-inputs';
                if (!q.options || q.options.length === 0 || (q.options[0] === '' && q.options[1] ===
                        '')) {
                    q.options = ['True', 'False'];
                }
                // Only set correctAnswerIndex to 0 if it is undefined/null and there are at least 2 non-empty options
                const nonEmptyOptions = (q.options || []).filter(opt => opt && opt.trim() !== '');
                if (typeof q.correctAnswerIndex !== 'number' || q.correctAnswerIndex < 0 || q
                    .correctAnswerIndex >= nonEmptyOptions.length) {
                    q.correctAnswerIndex = nonEmptyOptions.length >= 2 ? 0 : null;
                }
                let nonEmptyCount = 0;
                for (let o = 0; o < 4; o++) {
                    const optInput = document.createElement('input');
                    optInput.type = 'text';
                    optInput.className = 'quiz-option-input';
                    optInput.value = q.options[o] || '';
                    optInput.placeholder = `Option ${o+1}`;
                    // Save the current cursor position
                    optInput.oninput = (e) => {
                        const cursorPos = e.target.selectionStart;
                        quizData[idx].options[o] = e.target.value;
                        // Use a timeout to restore focus and cursor after re-render
                        setTimeout(() => {
                            renderQuiz();
                            const newInput = document.querySelectorAll(
                                '.quiz-question-row')[idx].querySelectorAll(
                                '.quiz-option-input')[o];
                            if (newInput) {
                                newInput.focus();
                                newInput.setSelectionRange(cursorPos, cursorPos);
                            }
                        }, 0);
                    };
                    // Only show radio for non-empty options
                    const radioLabel = document.createElement('label');
                    radioLabel.style.display = 'flex';
                    radioLabel.style.alignItems = 'center';
                    radioLabel.style.padding = '0.1rem 0';
                    if (optInput.value.trim() !== '') {
                        nonEmptyCount++;
                        const radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = `correct-answer-${idx}`;
                        radio.value = o;
                        radio.checked = (q.correctAnswerIndex === o);
                        radio.style.marginRight = '0.5rem';
                        radio.onchange = () => {
                            quizData[idx].correctAnswerIndex = o;
                            renderQuiz();
                        };
                        // Highlight the label if selected
                        if (q.correctAnswerIndex === o) {
                            radioLabel.style.background = '#d1e7dd';
                            radioLabel.style.borderRadius = '5px';
                        }
                        radioLabel.appendChild(radio);
                    }
                    radioLabel.appendChild(optInput);
                    optionsDiv.appendChild(radioLabel);
                }
                row.appendChild(optionsDiv);
                quizContainer.appendChild(row);
            });
        }

        // Add question button logic
        function setupAddButton() {
            let addBtn = document.getElementById('addQuestionBtn');
            if (!addBtn) {
                addBtn = document.createElement('button');
                addBtn.type = 'button';
                addBtn.className = 'quiz-add-btn';
                addBtn.id = 'addQuestionBtn';
                addBtn.textContent = '+';
                document.getElementById('quizEditForm').appendChild(addBtn);
            }
            addBtn.onclick = function() {
                quizData.push({
                    questionText: '',
                    options: ['True', 'False']
                });
                renderQuiz();
            };
        }

        // On form submit, serialize quizData to hidden input
        const quizEditForm = document.getElementById('quizEditForm');
        const quizDataInput = document.getElementById('quizDataInput');
        if (quizEditForm && quizDataInput) {
            quizEditForm.addEventListener('submit', function(e) {
                // Validation: Each question must have at least 2 non-empty options
                let valid = true;
                let emptyQuestion = false;
                for (const q of quizData) {
                    if (!q.questionText || q.questionText.trim() === '') {
                        emptyQuestion = true;
                        break;
                    }
                }
                if (emptyQuestion) {
                    e.preventDefault();
                    alert('Each question must have a non-empty question text.');
                    return false;
                }
                for (const q of quizData) {
                    const nonEmpty = (q.options || []).filter(opt => opt && opt.trim() !== '');
                    if (nonEmpty.length < 2) {
                        valid = false;
                        break;
                    }
                }
                if (!valid) {
                    e.preventDefault();
                    alert('Each question must have at least 2 non-empty options.');
                    return false;
                }
                quizDataInput.value = JSON.stringify(quizData);
            });
        }

        if (showQuizBtn && quizModal && modalBox) {
            showQuizBtn.addEventListener('click', function() {
                quizModal.style.display = 'flex';
                setTimeout(() => quizModal.classList.add('active'), 10);
                renderQuiz();
                setupAddButton();
            });
            quizModal.addEventListener('click', function(e) {
                if (e.target === quizModal) {
                    quizModal.classList.remove('active');
                    setTimeout(() => quizModal.style.display = 'none', 400);
                }
            });
            modalBox.addEventListener('mousedown', function() {
                modalBox.classList.add('transparent');
            });
            modalBox.addEventListener('mouseup', function() {
                modalBox.classList.remove('transparent');
            });
            modalBox.addEventListener('mouseleave', function() {
                modalBox.classList.remove('transparent');
            });
        }
    });
</script>
