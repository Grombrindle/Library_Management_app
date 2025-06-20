@props(['errors' => []])
<x-web-layout>
    <style>
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 2rem;
        }

        .auth-card {
            background-color: var(--card-bg);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .auth-title {
            color: var(--text-color);
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid var(--card-border);
            background-color: var(--dropdown-bg);
            color: var(--text-color);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--select-hover);
            box-shadow: 0 0 0 3px rgba(85, 81, 132, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color-inverted);
            background-color: var(--welcome-btn);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: var(--select-hover);
        }

        .auth-switch-link {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: var(--text-color);
        }

        .auth-switch-link a {
            color: var(--select-bg);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-switch-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .dashboard-link-container {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--card-border);
        }

        .dashboard-link {
            font-size: 1rem;
            color: var(--text-color);
        }

        .dashboard-link a {
            color: var(--select-bg);
            font-weight: 600;
            text-decoration: none;
        }
        .dashboard-link a:hover {
            text-decoration: underline;
        }

    </style>

    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">{{ __('messages.register') }}</h1>

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="userName" class="form-label">{{ __('messages.username') }}</label>
                    <input type="text" id="userName" name="userName" class="form-control" value="{{ old('userName') }}" required autofocus>
                    @error('userName')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('messages.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('messages.confirmPassword') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn-submit">{{ __('messages.register') }}</button>
            </form>

            <div class="auth-switch-link">
                <p>{{ __('messages.alreadyHaveAccount') }} <a href="{{ route('login') }}">{{ __('messages.login') }}</a></p>
            </div>

            <div class="dashboard-link-container">
                <p class="dashboard-link">
                    {{ __('messages.areYouTeacherOrAdmin') }} 
                    <a href="{{ route('admin.login') }}">{{ __('messages.loginHere') }}</a>
                </p>
            </div>
        </div>
    </div>
</x-web-layout>

<script>
@if ($errors->any())
    let msgs = @json($errors->all());
    alert(msgs.join('\n'));
@endif
</script> 