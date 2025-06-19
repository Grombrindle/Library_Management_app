@props(['link' => '#', 'image' => null, 'object'])
<style>
    .Object {
        background: var(--card-bg);
        margin-top: clamp(1%, 2vw, 2%);
        font-size: clamp(14px, 1.5vw + 8px, 20px);
        border: var(--card-border) clamp(2px, 0.5vw, 4px) solid;
        color: var(--text-color);
        border-radius: clamp(2px, 0.5vw, 3px);
        display: flex;
        flex-direction: row;
        transition: all 0.3s ease;
        transform: translateY(-2px);
        align-items: center;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: clamp(150px, 80vw, 800px);
        margin-left: auto;
        margin-right: auto;
    }

    .Object:hover {
        box-shadow: 0 0.25rem 0.25rem 0.1rem #121212;
        background-color: var(--card-bg);
        border: var(--card-bg) 4px solid;
        border-radius: 10px;
        color: var(--text-color);
        animation: hover ease 2s infinite;
    }

    .disable-hover .Object:hover {
        background: var(--card-bg);
        margin-top: 2%;
        font-size: 20px;
        border: var(--card-bg) 4px solid;
        color: var(--text-color);
        border-radius: 3px;
        display: flex;
        flex-direction: row;
        transition: 0.3s ease;
        align-items: center;
    }

    .textContainer {
        display: flex;
        flex-direction: column;
        line-height: 150%;
        z-index: 2;
        width: clamp(60%, 70vw, 80%);
        padding: clamp(2%, 3vw, 4%);
    }

    .circle {
        filter: blur(50px);
        position: absolute;
        width: 450px;
        height: 450px;
        background-color: #2E3061;
        border-radius: 50%;
        pointer-events: none;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        z-index: 1;
        transition: opacity 0.3s ease;
    }

    .Object:hover .circle {
        opacity: 0.15;
    }

    .image-container {
        margin: clamp(0.5%, 1vw, 1%);
        width: clamp(15%, 18vw, 20%);
        position: relative;
        padding-top: clamp(15%, 18vw, 20%);
        overflow: hidden;
        flex-shrink: 0;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .subject-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: scale-down;
        z-index: 2;
    }

    @keyframes hover {
        0% {
            transform: translateY(5px);
        }
        50% {
            transform: translateY(-5px);
        }
        100% {
            transform: translateY(5px);
        }
    }

    /* Enhanced disappear animation */
    @keyframes fadeAndRise {
        0% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        70% {
            opacity: 1;
            transform: translateY(-40px) scale(1.05);
        }
        100% {
            opacity: 0;
            transform: translateY(-80px) scale(0.95);
        }
    }

    .Object.disappear {
        animation: fadeAndRise 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        pointer-events: none;
    }

    .Object h2 {
        margin: 0 0 1rem 0;
        font-size: 1.25rem;
        color: var(--text-color);
    }

    .Object p {
        margin: 0.5rem 0;
        font-size: 1rem;
        color: var(--text-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .Object {
            flex-direction: column;
        }

        .textContainer {
            width: 100% !important;
            text-align: center;
        }

        .image-container {
            width: clamp(60%, 70vw, 80%) !important;
            padding-top: clamp(30%, 35vw, 40%) !important;
            margin: 0 auto clamp(5px, 1vw, 10px) auto;
        }
    }
</style>

<a href="/{{ $link }}" class="Object"
    style="@if ($image == null) justify-content:center; text-align:center; @endif">
    @if ($image != null)
        <div class="image-container">
            <img src="{{ $image }}" alt="{{ $object }} image" class="subject-image">
        </div>
        <div style="width:1px; height:100%; background-color:var(--text-color); margin-right:2%; margin-left:2%; z-index:2;"></div>
    @endif
    <div class="textContainer">{{ $slot }}</div>
    <div id="circle" class="circle"></div>
</a>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.Object');

        buttons.forEach(button => {
            const circle = button.querySelector('.circle');

            button.addEventListener('mousemove', (event) => {
                if (window.innerWidth > 768) { // Only apply on larger screens
                    const buttonRect = button.getBoundingClientRect();
                    const mouseX = event.clientX - buttonRect.left;
                    const mouseY = event.clientY - buttonRect.top;

                    circle.style.left = `${mouseX}px`;
                    circle.style.top = `${mouseY}px`;
                    circle.style.opacity = '1';
                }
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
                }, 200);
            });
        });
    });
</script>
<script>
    function bindDisappearAnimations() {
        document.querySelectorAll('.Object:not(.disappear)').forEach(card => {
            card.addEventListener('click', function() {
                this.classList.add('disappear');
            });
        });
    }

    // Initial binding
    bindDisappearAnimations();

    // Re-bind after filtering/searching (call this after DOM updates)
    function refreshAnimations() {
        bindDisappearAnimations(); // Re-attach to new/filtered cards
    }
</script>

