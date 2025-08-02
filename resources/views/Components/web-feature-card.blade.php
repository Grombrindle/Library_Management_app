@props(['icon', 'title', 'description', 'link', 'linkText'])

<div class="bg-[#f8f8fa] rounded-2xl p-8 card-hover">
    <div class="feature-icon mb-6">
        <i class="fas {{ $icon }} text-[#b0b0cf] text-2xl"></i>
    </div>
    <h3 class="text-xl font-bold mb-3">{{ $title }}</h3>
    <p class="text-[#68687a] mb-4">{{ $description }}</p>
    <a href="{{ $link }}" class="text-[#b0b0cf] font-medium flex items-center">
        {{ $linkText }} <i class="fas fa-arrow-right ml-2 text-sm"></i>
    </a>
</div> 