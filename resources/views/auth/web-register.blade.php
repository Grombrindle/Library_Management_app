<x-web-layout>

    <div style="max-width:400px;margin:40px auto;padding:30px;border-radius:10px;background:#fff;box-shadow:0 2px 8px #ccc;">
        <h2 style="text-align:center;">Register</h2>
        <form method="POST" action="{{ route('web.register') }}">
        @csrf
        <div style="margin-bottom:15px;">
            <label>Username</label>
            <input type="text" name="userName" value="{{ old('userName') }}" required autofocus style="width:100%;padding:8px;margin-top:5px;">
            @error('userName')<div style="color:red;font-size:0.9em;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:15px;">
            <label>Password</label>
            <input type="password" name="password" required style="width:100%;padding:8px;margin-top:5px;">
            @error('password')<div style="color:red;font-size:0.9em;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:15px;">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required style="width:100%;padding:8px;margin-top:5px;">
        </div>
        <button type="submit" style="width:100%;padding:10px;background:#333;color:#fff;border:none;border-radius:5px;">Register</button>
    </form>
    <div style="margin-top:15px;text-align:center;">
        <a href="{{ route('web.login') }}">Already have an account? Login</a>
    </div>
</div>
</x-web-layout>