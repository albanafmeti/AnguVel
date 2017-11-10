<?php

namespace App\Http\Controllers\Api;

use App\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ], [
            'email.required' => 'Ju lutem vendosni nje email.',
            'email.email' => 'Ju lutem vendosni nje email te sakte.'
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            Subscriber::create([
                'email' => $request->email
            ]);
            return response()->json([
                "success" => true,
                "message" => "Abonimi u krye me sukses."
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Ju jeni tashme i abonuar."
        ]);

    }
}
