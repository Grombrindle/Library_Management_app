@props(['number', 'title', 'description'])

<div class="bg-[#f8f8fa] rounded-2xl p-8 text-center">
    <div class="text-5xl font-bold text-[#b0b0cf] mb-4">{{ $number }}</div>
    <h3 class="text-xl font-bold mb-2">{{ $title }}</h3>
    <p class="text-[#68687a]">{{ $description }}</p>
</div> 