<?php

namespace App\Http\Controllers;

use App\Models\SavedMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedMessageController extends Controller
{
    //
    public function toggleSaved(Request $request) {
        if($request->id == null) {
            $message = SavedMessage::create([
                'text' => $request->text,
                'user_id' => Auth::id(),
                'date' => now()->subDays(rand(0, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
            ]);

            $message->save();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        }

        $message = SavedMessage::find($request->id);
        if(!$message) {
            return response()->json([
                'success' => false,
                'reason' => 'Not Found'
            ], 404);
        }
        if($message->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'reason' => 'Unauthorized'
            ], 403);
        }
        $message->delete();

        return response()->json([
            'success' => true,
        ]);

    }
}
