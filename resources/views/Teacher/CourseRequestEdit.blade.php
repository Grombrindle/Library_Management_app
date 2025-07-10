<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('teacher.course_requests.index'), __('messages.courseRequestEdit') => Request::url()]" />
    <x-cardcontainer :model="[]" addLink="" :showNameSort="false" num="0">

        <input type="text" name="image" class="form-control" value="{{ $request->image }}">
        </div>
        <div class="form-group mb-3">
            <label for="sources">{{ __('messages.courseRequestSources') }} (one per line)</label>
            <textarea name="sources_text" id="sources_text"
                class="form-control">{{ implode('\n', $request->sources ?? []) }}</textarea>
            <div id="sources_hidden_container"></div>
        </div>
        <div class="form-group mb-3">
            <label for="price">{{ __('messages.courseRequestPrice') }}</label>
            <input type="text" name="price" class="form-control" value="{{ $request->price }}">
        </div>
        <button type="submit" class="btn btn-success w-100">{{ __('messages.courseRequestResubmit') }}</button>
        </form>
        <script>
            document.querySelector('form').addEventListener('submit', function (e) {
                let sourcesText = document.getElementById('sources_text').value;
                let sourcesArray = sourcesText.split('\n').filter(function (el) {
                    return el.trim() !== '';
                });
                let container = document.getElementById('sources_hidden_container');
                container.innerHTML = '';
                sourcesArray.forEach(function (source) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'sources[]';
                    input.value = source;
                    container.appendChild(input);
                });
            });
        </script>
        </x-card>
    </x-cardcontainer>
</x-layout>
