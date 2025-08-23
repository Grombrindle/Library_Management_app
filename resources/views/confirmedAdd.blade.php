<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.addConfirmationTitle') }}</title>
    <style>
        .logo {
            width: 20%;
            height: 7.5%;
            margin-right: auto;
            margin-left: auto;
        }

        .logoContainer {
            height: auto;
            width: auto;
            margin-top: 5%;
            display: flex;
            flex-direction: row;
        }

        .message-container {
            text-align: center;
            margin-top: 2%;
            font-size: 2.5rem;
            color: var(--text-color);
        }

        .message-container h1 {
            color: var(--text-color);
        }

        .message-container p {
            color: var(--text-color);
        }
    </style>
    <script>
        const link = @json(session('link', '/welcome'));
        console.log(link);
        // Redirect after 3 seconds
        setTimeout(() => {
            window.location.href = link;
        }, 3000);
    </script>
</head>
<x-layout :nav=false>
    <div class="logoContainer">
        <img src="{{ asset('Images/Web/MindSpark outline.png') }}" alt="" class="logo">
    </div>
    <div class="message-container">
        @if (session('add_info'))
            @php
                $info = session('add_info');
            @endphp
            <h1>
                {{ __('messages.addedSuccessfully', ['name' => $info['name']]) }}
            </h1>
        @else
            <h1>{{ __('messages.nothingToConfirm') }}</h1>
        @endif
        <p>{{ __('messages.redirecting') }}</p>
    </div>
</x-layout>

</html>
