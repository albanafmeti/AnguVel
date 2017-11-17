<?php

namespace App\Http\Controllers;

use App\FbAppResult;
use Illuminate\Http\Request;

class FbAppsController extends Controller
{

    public function appResult_1000(Request $request)
    {
        $fbAppResult = FbAppResult::where("user_id", $request->userId)->first();
        return view('fbApps.result')->with('result', $fbAppResult);
    }
}