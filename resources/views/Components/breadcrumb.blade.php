@props(['align' => false, 'links' => [], 'currentUrl' => Request::url()])

<style>
    .breadcrumb {
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        list-style: none;
        background-color: var(--breadcrumb-bg);
        border-radius: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        border: var(--breadcrumb-border) 1px solid;
    }

    @media(max-width:1600px) {
        [dir="ltr"] .breadcrumb {
            font-size: 15px;
            margin-left: 12.5rem;
        }

        [dir="rtl"] .breadcrumb {
            font-size: 15px;
            margin-right: 10rem;
        }
    }

    @media(max-width:800px) {
        [dir="ltr"] .breadcrumb {
            font-size: 12.5px;
            margin-left: 10rem;
        }

        [dir="rtl"] .breadcrumb {
            font-size: 12.5px;
            margin-right: 10rem;
        }
    }

    @media(max-width:600px) {
        [dir="ltr"] .breadcrumb {
            font-size: 10px;
            margin-left: 7.5rem;
        }

        [dir="rtl"] .breadcrumb {
            font-size: 10px;
            margin-right: 7.5rem;
        }
    }

    @media(max-width:400px) {
        [dir="ltr"] .breadcrumb {
            font-size: 7.5px;
            margin-left: 5rem;
        }

        [dir="rtl"] .breadcrumb {
            font-size: 7.5px;
            margin-right: 5rem;
        }
    }

    [dir="ltr"] .breadcrumb {
            font-size: 12.5px;
            margin-left: 12.5rem;
        }

        [dir="rtl"] .breadcrumb {
            font-size: 12.5px;
            margin-right: 12.5rem;
        }

    .breadcrumb-item {
        display: flex;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: ">>";
        padding: 0 0.5rem;
        color: var(--text-color);
    }

    .breadcrumb-item a {
        color: #007BFF;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--text-color);
        pointer-events: none;
        cursor: default;
    }
</style>

<div style="display:flex; flex-direction:row; width:100%;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($links as $label => $url)
                @if ($url && $url !== $currentUrl)
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
