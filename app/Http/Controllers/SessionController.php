<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Session\SessionService;

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
}