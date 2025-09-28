<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SavedMessages\SavedMessageService;

class SavedMessageController extends Controller
{
    protected $service;

    public function __construct(SavedMessageService $service)
    {
        $this->service = $service;
    }

    public function fetch() {

        $result = $this->service->fetch();

        // Handle error codes if present
        if (isset($result['code'])) {
            return response()->json($result);
        }

        return response()->json($result);

    }

    public function toggleSaved(Request $request)
    {
        $result = $this->service->toggleSaved($request);

        // Handle error codes if present
        if (isset($result['code'])) {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }
}