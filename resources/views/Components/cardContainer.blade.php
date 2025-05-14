@props([
    'model',
    'addLink',
    'filterOptions' => [],
    'showSubjectCountFilter' => false,
    'showUsernameSort' => false,
    'showNameSort' => false, // New prop to control name sorting visibility
    'filterByTeachers' => false,
    'showPrivilegeFilter' => false, // New prop to control privilege filter visibility
    'num' => null,
    'deleteSubs' => false,
    'showBannedFilter' => false,
])

<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<style>
    .ObjectContainer {
        width: 81%;
        height: auto;
        display: flex;
        flex-direction: row;
    }

    .container {
        width: 50%;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .addButton {
        background-color: rgb(41, 200, 41);
        border: black 2px solid;
        text-decoration: none;
        color: black;
        border-radius: 10px;
        width: 10rem;
        height: 100%;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.5s ease;
    }

    [dir="rtl"] .addButton {
        margin-right: auto;
    }

    [dir="ltr"] .addButton {
        margin-left: auto;
    }

    .addButton:hover {
        background-color: #FFFFFF;
        border-color: #29C829;
        color: #29C829;
        box-shadow: 0 0 0.5rem 0 #29C829;

    }

    .search-bar {
        width: 50%;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 10px;
        font-size: 1.5rem;
        padding-left: 2.5rem;
        align-self: flex-start;
        margin-right: auto;
    }

    .chunk {
        width: 50%;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        margin-bottom: 20px;
        margin-left: 0.5%;
        margin-right: 0.5%;
    }

    #search-form {
        position: relative;
        width: 100%;
    }

    #search-form button {
        background: none;
        border: none;
        cursor: pointer;
        position: absolute;
        transform: translateY(-50%);
    }

    [dir="ltr"] #search-form button {
        left: 1%;
    }

    [dir="rtl"] #search-form button {
        left: 75%;
    }

    #search-form .material-symbols-outlined {
        font-size: 16px;
        color: #666;
    }

    .filter-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        background-color: var(--filter-bg);
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        z-index: 1000;
        display: none;
        width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: var(--filter-text);
    }

    .filter-dropdown.show {
        display: block;
    }

    .filter-dropdown label {
        display: block;
        margin: 5px 0;
    }

    .filter-dropdown input[type="radio"],
    .filter-dropdown input[type="checkbox"] {
        margin-right: 10px;
    }

    .filter-columns {
        display: flex;
        flex-wrap: wrap;
    }

    .filter-column {
        flex: 1;
        min-width: 150px;
    }

    .filter-buttons {
        margin-top: 10px;
    }

    .filter-buttons button {
        margin-right: 10px;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
    }

    #filter-button {
        padding: 0;
        right: 0;
        width: 1%;
        margin-left: auto;
        margin-right: auto;
    }

    /* Add to your existing styles */
    .ObjectContainer {
        perspective: 1200px;
        /* Stronger 3D perspective */
    }

    .Object {
        transition:
            transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 1.1),
            opacity 0.3s ease,
            filter 0.3s ease;
        transform-style: preserve-3d;
        will-change: transform, box-shadow;
    }

    /* Hovered card - 3D pop effect */
    .Object:hover {
        transform:
            translateY(-12px) scale(1.03) rotateX(8deg) rotateY(2deg);
        box-shadow:
            0 20px 30px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        filter: brightness(1.1) drop-shadow(0 5px 5px rgba(0, 0, 0, 0.2));
        z-index: 20;
    }

    /* Non-hovered cards - subtle retreat */
    .ObjectContainer:has(.Object:hover) .Object:not(:hover) {
        opacity: 0.6;
        transform:
            translateZ(-20px) scale(0.96);
        filter:
            brightness(0.8) blur(1px);
    }

    /* Circle effect enhancement */
    .Object:hover .circle {
        opacity: 1;
        transition:
            opacity 0.3s ease,
            transform 0.4s cubic-bezier(0.68, -0.6, 0.32, 1.6);
    }

    .Object:hover {
        background: linear-gradient(135deg,
                rgba(85, 81, 132, 1) 0%,
                rgba(95, 91, 142, 1) 100%);
    }


    .deleteSubs {
        background-color: red;
        border: 0.15rem white solid;
        text-decoration: none;
        font-size: 20px;
        color: black;
        text-align: center;
        height: fit-content;
        width: fit-content;
        font-family: 'Pridi';
        padding: 0.5rem 0.5rem;
        border-radius: 1rem;
        transition: 0.5s ease;
        cursor: pointer;
    }

    [dir="ltr"] .container form {
        margin-left: auto;
    }

    [dir="rtl"] .container form {
        margin-right: auto;
    }

    .deleteSubs:hover {
        border-color: red;
        background-color: black;
        color: red;
    }
</style>
<div style="width:80%; display:flex;flex-direction:row;">
    <!-- Search Form -->
    <div class="container">
        <form id="search-form">
            <input type="text" class="search-bar" name="search" placeholder="{{ __('messages.search') }}"
                value="{{ request('search') }}">
            <button type="submit" style="top:50%;">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
            <button type="button" id="filter-button" style="top:50%;">
                <span class="material-symbols-outlined">
                    filter_alt
                </span>
            </button>
            <div class="filter-dropdown" id="filter-dropdown">
                <label><strong>{{ __('messages.sortBy') }}</strong></label>
                <!-- Newest/Oldest -->
                <label><input type="radio" name="sort" value="newest"
                        {{ request('sort', 'newest') === 'newest' ? 'checked' : '' }}> {{ __('messages.newest') }}</label>
                <label><input type="radio" name="sort" value="oldest"
                        {{ request('sort') === 'oldest' ? 'checked' : '' }}> {{ __('messages.oldest') }}</label>

                <!-- Name A-Z/Z-A (conditional) -->
                @if ($showNameSort)
                    <label><input type="radio" name="sort" value="name-a-z"
                            {{ request('sort') === 'name-a-z' ? 'checked' : '' }}> {{ __('messages.nameAZ') }}</label>
                    <label><input type="radio" name="sort" value="name-z-a"
                            {{ request('sort') === 'name-z-a' ? 'checked' : '' }}> {{ __('messages.nameZA') }}</label>
                @endif

                <!-- Username A-Z/Z-A (conditional) -->
                @if ($showUsernameSort)
                    <label><input type="radio" name="sort" value="username-a-z"
                            {{ request('sort') === 'username-a-z' ? 'checked' : '' }}> {{ __('messages.usernameAZ') }}</label>
                    <label><input type="radio" name="sort" value="username-z-a"
                            {{ request('sort') === 'username-z-a' ? 'checked' : '' }}> {{ __('messages.usernameZA') }}</label>
                @endif

                <!-- Filter by Privileges (conditional) -->
                @if ($showPrivilegeFilter)
                    <label><strong>{{ __('messages.filterByPrivileges') }}</strong></label>
                    <label><input type="checkbox" name="privileges[]" value="2"
                            {{ in_array('2', request('privileges', [])) ? 'checked' : '' }}> {{ __('messages.admin') }}</label>
                    <label><input type="checkbox" name="privileges[]" value="1"
                            {{ in_array('1', request('privileges', [])) ? 'checked' : '' }}> {{ __('messages.semiAdmin') }}</label>
                    <label><input type="checkbox" name="privileges[]" value="0"
                            {{ in_array('0', request('privileges', [])) ? 'checked' : '' }}> {{ __('messages.teacher') }}</label>
                @endif

                <!-- Filter by Subjects (for users and teachers) -->
                @if (!empty($filterOptions) && !$filterByTeachers)
                    <label><strong>{{ __('messages.filterBySubject') }}</strong></label>
                    <div style="margin: 0 0; padding: 10px 0;">
                        <button type="button" id="toggle-all"
                                style="margin-left: 10px; padding: 5px 10px; border: 1px solid var(--filter-text); border-radius: 4px; cursor: pointer; color: var(--filter-text);">
                            {{ __('messages.selectAll') }}
                        </button>
                    </div>
                    <div class="filter-columns" style="margin-top:4%;">
                        @foreach (array_chunk($filterOptions, 6, true) as $chunk)
                            <div class="filter-column">
                                @foreach ($chunk as $key => $value)
                                    <label><input type="checkbox" name="subjects[]" value="{{ $key }}"
                                            {{ in_array($key, request('subjects', [])) ? 'checked' : '' }}>
                                        {{ $value }}</label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Filter by Teachers (for subjects) -->
                @if (!empty($filterOptions) && $filterByTeachers)
                    <label><strong>{{ __('messages.filterByTeachers') }}</strong></label>
                    <button type="button" id="toggle-all"
                    style="top:25.5%;padding: 5px 10px; border: 1px solid #000000; border-radius: 4px; cursor: pointer;">{{ __('messages.selectAll') }}</button>
                    <div class="filter-columns" style="margin-top:4%;">
                        @foreach (array_chunk($filterOptions, 6, true) as $chunk)
                            <div class="filter-column">
                                @foreach ($chunk as $key => $value)
                                    <label><input type="checkbox" name="teachers[]" value="{{ $key }}"
                                            {{ in_array($key, request('teachers', [])) ? 'checked' : '' }}>
                                        {{ $value }}</label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Filter by Number of Subjects (for users and teachers) -->
                @if ($showSubjectCountFilter && !$filterByTeachers)
                    <label><strong>{{ __('messages.filterBySubjectCount') }}</strong></label>
                    <label><input type="checkbox" name="none" id="filter-none"
                            {{ request('none') ? 'checked' : '' }}> {{ __('messages.none') }}</label>
                    <label><input type="checkbox" name="subject_count[]" value="1"
                            {{ in_array('1', request('subject_count', [])) ? 'checked' : '' }}> 1</label>
                    <label><input type="checkbox" name="subject_count[]" value="2-3"
                            {{ in_array('2-3', request('subject_count', [])) ? 'checked' : '' }}> 2-3</label>
                    <label><input type="checkbox" name="subject_count[]" value="4-5"
                            {{ in_array('4-5', request('subject_count', [])) ? 'checked' : '' }}> 4-5</label>
                    <label><input type="checkbox" name="subject_count[]" value="6+"
                            {{ in_array('6+', request('subject_count', [])) ? 'checked' : '' }}> 6+</label>
                @endif

                <!-- Filter by Number of Teachers (for subjects) -->
                @if ($showSubjectCountFilter && $filterByTeachers)
                    <label><strong>{{ __('messages.filterByTeacherCount') }}</strong></label>
                    <label><input type="checkbox" name="none" id="filter-none"
                            {{ request('none') ? 'checked' : '' }}> {{ __('messages.none') }}</label>
                    <label><input type="checkbox" name="teacher_count[]" value="1"
                            {{ in_array('1', request('teacher_count', [])) ? 'checked' : '' }}> 1</label>
                    <label><input type="checkbox" name="teacher_count[]" value="2-3"
                            {{ in_array('2-3', request('teacher_count', [])) ? 'checked' : '' }}> 2-3</label>
                    <label><input type="checkbox" name="teacher_count[]" value="4-5"
                            {{ in_array('4-5', request('teacher_count', [])) ? 'checked' : '' }}> 4-5</label>
                    <label><input type="checkbox" name="teacher_count[]" value="6+"
                            {{ in_array('6+', request('teacher_count', [])) ? 'checked' : '' }}> 6+</label>
                @endif

                @if ($showBannedFilter)
                    <label><strong>{{ __('messages.filterByBanStatus') }}</strong></label>
                    <label><input type="radio" name="ban_status" value="all"
                            {{ request('ban_status', 'all') === 'all' ? 'checked' : '' }}> {{ __('messages.allUsers') }}</label>
                    <label><input type="radio" name="ban_status" value="banned"
                            {{ request('ban_status') === 'banned' ? 'checked' : '' }}> {{ __('messages.bannedOnly') }}</label>
                    <label><input type="radio" name="ban_status" value="active"
                            {{ request('ban_status') === 'active' ? 'checked' : '' }}> {{ __('messages.activeOnly') }}</label>
                @endif
            </div>
        </form>
    </div>

    <div class="container">
        @if ($addLink != null)
            <a href="/{{ $addLink }}" class="addButton">{{Str::upper(__('messages.add'))}}</a>
        @endif
        @if ($deleteSubs != false)
            <form action="/deletesubs" method="POST" onsubmit="return validateSubs()">
                @csrf
                @method('PUT')
                <button type="submit" class="deleteSubs">{{ __('messages.deleteSubs') }}</button>
            </form>
        @endif
    </div>
</div>

<div class="ObjectContainer">
    {{ $slot }}
</div>

<script>
    function attachCircleEffect() {
        const buttons = document.querySelectorAll('.Object'); // Select all buttons

        buttons.forEach(button => {
            const circle = button.querySelector('.circle'); // Select the circle inside the button

            button.addEventListener('mousemove', (event) => {
                const buttonRect = button.getBoundingClientRect();
                const mouseX = event.clientX - buttonRect
                    .left; // Calculate mouse position relative to the button
                const mouseY = event.clientY - buttonRect.top;

                // Position the circle at the mouse cursor
                circle.style.left = `${mouseX}px`;
                circle.style.top = `${mouseY}px`;
                circle.style.opacity = '1';
            });

            button.addEventListener('mouseleave', () => {
                circle.style.opacity = '0';
            });

            document.addEventListener('scroll', () => {
                circle.style.opacity = '0';
            });

            let scrollTimer;
            document.addEventListener('scroll', () => {
                document.body.classList.add('disable-hover');
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    document.body.classList.remove('disable-hover');
                }, 2000);
            });
            // Inside your existing attachCircleEffect() function:
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                // Trigger animation
                this.classList.add('disappear');

                // Wait for animation to complete before navigation
                setTimeout(() => {
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                }, 1000); // Shorter than animation duration
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachCircleEffect();
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');
        const filterButton = document.getElementById('filter-button');
        const filterDropdown = document.getElementById('filter-dropdown');
        const toggleAllButton = document.getElementById('toggle-all');
        const filterNoneCheckbox = document.getElementById('filter-none');
        const filterForm = document.querySelector('.filter-dropdown');

        // Toggle filter dropdown
        if (filterButton && filterDropdown) {
            filterButton.addEventListener('click', function(event) {
                event.stopPropagation();
                filterDropdown.classList.toggle('show');
            });
        }

        // Close filter dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (filterDropdown && !filterDropdown.contains(event.target) && !filterButton.contains(event
                    .target)) {
                filterDropdown.classList.remove('show');
            }
        });

        // Update the toggle button text and functionality
        function updateToggleButton() {
            if (toggleAllButton) {
                const checkboxes = document.querySelectorAll(
                    'input[name="teachers[]"], input[name="subjects[]"]');
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                toggleAllButton.textContent = allChecked ? '{{ __("messages.deselectAll") }}' : '{{ __("messages.selectAll") }}';
            }
        }

        // Toggle all checkboxes
        if (toggleAllButton) {
            toggleAllButton.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll(
                    'input[name="teachers[]"], input[name="subjects[]"]');
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
                updateToggleButton();
                triggerFilterChange();
            });
        }

        // Update toggle button when checkboxes change
        const checkboxes = document.querySelectorAll('input[name="teachers[]"], input[name="subjects[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateToggleButton);
        });

        // Filter None checkbox
        if (filterNoneCheckbox) {
            filterNoneCheckbox.addEventListener('change', function() {
                triggerFilterChange();
            });
        }

        // Ban status radio buttons
        const banStatusRadios = document.querySelectorAll('input[name="ban_status"]');
        banStatusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                triggerFilterChange();
            });
        });

        // Main filter form change handler
        if (filterForm) {
            filterForm.addEventListener('change', function() {
                const selectedSort = document.querySelector('input[name="sort"]:checked').value;
                const selectedFilters = Array.from(document.querySelectorAll(
                    'input[type="checkbox"]:checked')).map(el => ({
                    name: el.name,
                    value: el.value
                }));
                const selectedBanStatus = document.querySelector('input[name="ban_status"]:checked')
                    ?.value || 'all';
                const searchQuery = searchBar.value;

                // Build the query string
                const params = new URLSearchParams();
                params.set('sort', selectedSort);
                params.set('ban_status', selectedBanStatus);

                selectedFilters.forEach(filter => {
                    if (filter.name === 'none') {
                        params.set('none', 'true');
                    } else {
                        params.append(filter.name, filter.value);
                    }
                });
                params.set('search', searchQuery);

                // Update the URL without reloading the page
                window.history.replaceState({}, '', `{{ request()->url() }}?${params.toString()}`);

                fetchResults(params);
            });
        }

        // Handle search input
        if (searchBar) {
            searchBar.addEventListener('input', function() {
                const query = searchBar.value;
                const selectedSort = document.querySelector('input[name="sort"]:checked')?.value ||
                    'newest';
                const selectedFilters = Array.from(document.querySelectorAll(
                    'input[type="checkbox"]:checked')).map(el => ({
                    name: el.name,
                    value: el.value
                }));
                const selectedBanStatus = document.querySelector('input[name="ban_status"]:checked')
                    ?.value || 'all';

                // Build the query string
                const params = new URLSearchParams();
                params.set('search', query);
                params.set('sort', selectedSort);
                params.set('ban_status', selectedBanStatus);

                selectedFilters.forEach(filter => {
                    if (filter.name === 'none') {
                        params.set('none', 'true');
                    } else {
                        params.append(filter.name, filter.value);
                    }
                });

                fetch(`{{ request()->url() }}?${params.toString()}`)
                    .then(response => response.text())
                    .then(data => {
                        updateContent(data);
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            });
        }

        // Function to trigger filter change
        function triggerFilterChange() {
            const event = new Event('change', {
                bubbles: true
            });
            if (filterForm) {
                filterForm.dispatchEvent(event);
            }
        }

        // Function to fetch results
        function fetchResults(params) {
            fetch(`{{ request()->url() }}?${params.toString()}`)
                .then(response => response.text())
                .then(data => {
                    updateContent(data);
                })
                .catch(error => console.error('Error fetching filtered results:', error));
        }

        // Function to update content
        function updateContent(data) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const newContent = doc.getElementById('dynamic-content').innerHTML;

            // Update the dynamic content
            dynamicContent.innerHTML = newContent;
            let num = @json($num);

            const paginationInfo = doc.querySelector('.pagination-info');
            const paginationInfoContainer = document.querySelector('.pagination-info');
            if (paginationInfo) {
                paginationInfoContainer.innerHTML = paginationInfo.innerHTML;
            } else {
                if (num > 10) {
                    paginationInfoContainer.innerHTML = '';
                }
            }

            // Update pagination links
            const pagination = doc.querySelector('.pagination');
            const paginationContainer = document.querySelector('.pagination');
            if (pagination) {
                paginationContainer.innerHTML = pagination.innerHTML;
            } else {
                if (num > 10) {
                    paginationContainer.innerHTML = '';
                }
            }

            // Reattach the circle effect
            attachCircleEffect();
        }
    });

    function validateSubs() {
        return confirm('{{ __("messages.confirmDeleteSubscriptions") }}');    }
</script>
