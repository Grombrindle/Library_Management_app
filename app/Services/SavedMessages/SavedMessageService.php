<?php

namespace App\Services\SavedMessages;

use App\Models\SavedMessage;
use Illuminate\Support\Facades\Auth;

class SavedMessageService
{
    public function fetch() {
        return [
            'success' => true,
            'messages' => SavedMessage::all()->where('user_id', Auth::id()),
        ];
    }
    public function toggleSaved($request)
    {
        // If no ID is provided, create a new saved message
        if (is_null($request->id)) {
            $message = new SavedMessage();
            $message->text = $request->text;
            $message->user_id = Auth::id();
            $message->date = now()
                ->subDays(rand(0, 365))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));
            $message->save();

            return ['success' => true, 'message' => $message];
        }

        // Otherwise, try to find and delete the existing saved message
        $message = SavedMessage::find($request->id);
        if (!$message) {
            return ['success' => false, 'reason' => 'Not Found', 'code' => 404];
        }

        if ($message->user_id != Auth::id()) {
            return ['success' => false, 'reason' => 'Unauthorized', 'code' => 403];
        }

        $message->delete();
        return ['success' => true];
    }
}