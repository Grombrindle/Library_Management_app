<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Session\SessionService;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    protected $service;

    public function __construct(SessionService $service)
    {
        $this->service = $service;
    }

    public function createUser(Request $request)
    {
        $result = $this->service->createUser($request);
        return isset($result['code']) ? response()->json($result, $result['code']) : response()->json($result);
    }

    public function loginUser(Request $request)
    {
        $result = $this->service->loginUser($request);
        return isset($result['code']) ? response()->json($result, $result['code']) : response()->json($result);
    }

    public function ban()
    {
        $result = $this->service->banCurrentUser();
        return isset($result['code']) ? response()->json($result, $result['code']) : response()->json($result);
    }

    public function banUser($id = null, $type = null, $ratingId = null)
    {
        $result = $this->service->banUser($id, $type, $ratingId);
        return redirect()->route('user.confirmation');
    }

    public function logoutUser()
    {
        $result = $this->service->logoutUser();
        return response()->json($result);
    }

    public function test()
    {
        return response()->json(['User' => auth()->user()]);
    }

    public function loginView()
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }
        return view('register');
    }

    public function loginWeb(Request $request)
    {
        $credentials = ['userName' => $request->userName, 'password' => $request->password];
        if (Auth::attempt($credentials)) {
            $admin = Admin::where('userName', $credentials['userName'])->first();
            if (Hash::check($credentials['password'], $admin->password)) {
                Auth::login($admin);
                return redirect('/welcome');
            }
        }
        return redirect()->back()->withErrors(['password' => 'Invalid Credentials'])->withInput(['userName']);

    }
}