<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.logoutConfirmationTitle') }}</title>

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
    </style>
    <script>
        // Redirect after 3 seconds using a POST request
        setTimeout(() => {
            // Create a form element
            const form = document.createElement('form');
            form.method = 'POST'; // Set the method to POST
            form.action = '/registerout'; // Set the action URL

            // Add a CSRF token (required for Laravel POST requests)
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}'; // Add the CSRF token
            form.appendChild(csrfToken);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        }, 3000);
    </script>
</head>

<x-layout :nav=false>

    <div class="logoContainer">
        <img src="Images/Web/MindSpark outline.png" alt="" class="logo">
    </div>
    <div style="text-align: center; margin-top: 2%; font-size:2.5rem; color: var(--text-color)">
        <h1>
            {{ __('messages.loggedOutSuccessfully') }}
            <br>
            {{ __('messages.thankYouForContributing') }}
        </h1>
    </div>

</x-layout>

</html>
