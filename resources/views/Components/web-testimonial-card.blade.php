@props(['name', 'role', 'image', 'content', 'rating' => 5])

<div class="testimonial-card rounded-2xl p-8">
    <div class="flex items-center mb-6">
        <div class="w-12 h-12 rounded-full bg-cover bg-center mr-4" style="background-image: url('{{ $image }}')"></div>
        <div>
            <h4 class="font-bold">{{ $name }}</h4>
            <p class="text-[#68687a] text-sm">{{ $role }}</p>
        </div>
    </div>
    <p class="text-[#68687a] mb-4">{{ $content }}</p>
    <div class="flex text-yellow-400">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $rating)
                <i class="fas fa-star"></i>
            @elseif ($i - 0.5 <= $rating)
                <i class="fas fa-star-half-alt"></i>
            @else
                <i class="far fa-star"></i>
            @endif
        @endfor
    </div>
</div> 