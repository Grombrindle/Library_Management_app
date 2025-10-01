<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SavedMessageService;

class SavedMessageController extends Controller
{
    protected $savedMessageService;

    public function __construct(SavedMessageService $savedMessageService)
    {
        $this->savedMessageService = $savedMessageService;
    }

    public function fetch() {

        $result = $this->savedMessageService->fetch();

        // Handle error codes if present
        if (isset($result['code'])) {
            return response()->json($result);
        }

        return response()->json($result);

    }

    public function toggleSaved(Request $request)
    {
        $result = $this->savedMessageService->toggleSaved($request);

        // Handle error codes if present
        if (isset($result['code'])) {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }
}
