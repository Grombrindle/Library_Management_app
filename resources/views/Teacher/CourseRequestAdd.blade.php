<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('teacher.course_requests.index'), __('messages.courseRequestAdd') => Request::url()]" />

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
            {{ __('messages.courseRequestAdd') }}
        </h2>

        <form method="POST" action="{{ route('teacher.course_requests.store') }}"
            class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestName') }}
                </label>
                <input type="text" name="name" id="name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestDescription') }}
                </label>
                <textarea name="description" id="description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 h-32 resize-y @error('description') border-red-500 @enderror"
                    required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="subject_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestSubject') }}
                </label>
                <select name="subject_id" id="subject_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('subject_id') border-red-500 @enderror"
                    required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestImage') }} (URL)
                </label>
                <input type="url" name="image" id="image"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('image') border-red-500 @enderror"
                    placeholder="e.g., https://example.com/image.jpg" value="{{ old('image') }}">
                @error('image')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="sources_text" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestSources') }} (one per line)
                </label>
                <textarea name="sources_text" id="sources_text"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 h-40 resize-y @error('sources') border-red-500 @enderror"
                    placeholder="Enter each source on a new line&#10;e.g., Book Title&#10;Website URL">{{ old('sources_text') }}</textarea>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                    Enter each source on a new line (e.g., Book Title, Website URL).
                </p>
                <div id="sources_hidden_container"></div>
                @error('sources')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="price" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    {{ __('messages.courseRequestPrice') }}
                </label>
                <input type="text" name="price" id="price"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('price') border-red-500 @enderror"
                    placeholder="e.g., 99.99" value="{{ old('price') }}">
                @error('price')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="lecturesCount" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    Lectures Count
                </label>
                <input type="number" name="lecturesCount" id="lecturesCount"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('lecturesCount') border-red-500 @enderror"
                    placeholder="e.g., 10" value="{{ old('lecturesCount') }}">
                @error('lecturesCount')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="subscriptions" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    Subscriptions
                </label>
                <input type="number" name="subscriptions" id="subscriptions"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('subscriptions') border-red-500 @enderror"
                    placeholder="e.g., 100" value="{{ old('subscriptions') }}">
                @error('subscriptions')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-center">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-150 ease-in-out">
                    {{ __('messages.courseRequestSubmit') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            let sourcesText = document.getElementById('sources_text').value;
            let sourcesArray = sourcesText.split('\n').filter(function (el) {
                return el.trim() !== '';
            });
            let container = document.getElementById('sources_hidden_container');
            container.innerHTML = ''; // Clear previous hidden inputs
            sourcesArray.forEach(function (source) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'sources[]'; // Use 'sources[]' for array input
                input.value = source.trim(); // Trim whitespace from each source
                container.appendChild(input);
            });
        });
    </script>
</x-layout>
