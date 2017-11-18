<?php

namespace App\Http\Controllers;

use App\FbAppResult;
use Illuminate\Http\Request;

class FbAppsController extends Controller
{

    public function result(Request $request, $appId)
    {
        switch ($appId) {
            case '1000':
                return $this->app1000($request);
                break;
        }
    }

    public function app1000(Request $request)
    {
        $fbAppResult = FbAppResult::where("user_id", $request->userId)->first();
        return view('fbApps.result')->with('result', $fbAppResult);
    }
}