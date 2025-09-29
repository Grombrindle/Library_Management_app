<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SessionService;
use App\Models\User;
use App\Models\Admin;

class SessionController extends Controller
{
    protected $service;

    public function __construct(SessionService $service)
    {
        $this->service = $service;
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'userName' => 'required|string|unique:users,userName',
            'number' => 'required|string|unique:users,number',
            'password' => 'required|string|min:6|confirmed',
            'countryCode' => 'nullable|string|max:5',
        ]);

        $result = $this->service->createUser($data);

        return response()->json([
            'success' => true,
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->service->loginUser($credentials);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'user' => $result['user'],
                'token' => $result['token']
            ]);
        }

        return response()->json([
            'success' => false,
            'reason' => $result['message']
        ], 401);
    }

    public function loginWeb(Request $request)
    {
        $credentials = $request->only('userName', 'password');

        if ($this->service->loginWeb($credentials)) {
            return redirect('/welcome');
        }

        return redirect()->back()->withErrors(['password' => 'Invalid Credentials'])->withInput(['userName']);
    }

    public function logoutUser(Request $request)
    {
        $this->service->logoutUser();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }

    public function ban()
    {
        $user = Auth::user();
        $result = $this->service->banUser($user);

        if ($result['success']) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'reason' => $result['message']], 400);
    }

    public function banUser($id, $type = null, $ratingId = null)
    {
        $user = $id ? User::findOrFail($id) : Auth::user();
        $this->service->banUser($user, session('report'), $type, $ratingId);

        return redirect()->route('user.confirmation');
    }

    public function test()
    {
        return response()->json([
            'User' => $this->service->currentUser()
        ]);
    }

    public function loginView()
    {
        if (auth()->check()) {
            return redirect()->route('welcome');
        }
        return view('register');
    }
}