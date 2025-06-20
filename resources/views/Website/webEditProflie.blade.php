<x-web-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold mb-8">Edit Profile</h2>
                
                <form method="POST" action="{{ route('web.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex flex-col items-center mb-8">
                        <div class="relative mb-4">
                            <div class="w-32 h-32 rounded-full bg-cover bg-center border-4 border-[#b0b0cf]" 
                                 style="background-image: url('{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://via.placeholder.com/150' }}')"></div>
                            <label class="absolute bottom-0 right-0 w-10 h-10 rounded-full bg-[#b0b0cf] border-2 border-white flex items-center justify-center cursor-pointer">
                                <i class="fas fa-camera text-white text-sm"></i>
                                <input type="file" name="avatar" class="hidden">
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm">Click camera icon to change avatar</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 mb-2">Username</label>
                            <input type="text" name="userName" value="{{ old('userName', Auth::user()->userName) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b0b0cf] focus:border-transparent">
                            @error('userName')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Phone Number</label>
                            <div class="flex gap-2">
                                <select name="countryCode" class="w-1/3 px-4 py-3 border border-gray-300 rounded-lg">
                                    @foreach(['+1', '+20', '+33', '+49', '+90'] as $code)
                                        <option value="{{ $code }}" {{ $code == Auth::user()->countryCode ? 'selected' : '' }}>{{ $code }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="number" value="{{ old('number', Auth::user()->number) }}"
                                       class="w-2/3 px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            @error('number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('web.profile') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-web-layout>