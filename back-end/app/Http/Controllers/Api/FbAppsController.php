<?php

namespace App\Http\Controllers\Api;

use App\FbAppResult;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class FbAppsController extends Controller
{

    public function appResult_1000(Request $request)
    {
        $fbAppResult = FbAppResult::where("user_id", $request->id)->first();

        if (!$fbAppResult) {

            $car = $this->calculateCar($request->id);

            $userProfilePic = Image::make($request->picture['data']['url'])->fit(250, 250);
            $carPic = Image::make(public_path("assets/images/fb/apps/1000/cars/$car"))->fit(1200, 630);

            $canvas = Image::canvas(1200, 630);
            $canvas->insert($carPic, 'center');
            $canvas->insert($userProfilePic, 'top-left');
            $canvas->encode('jpg', 100)->save(public_path("assets/images/fb/apps/1000/results/{$request->id}.jpg"));
            $fbAppResult = FbAppResult::create([
                "app_id" => "1000",
                "user_id" => $request->id,
                "user_name" => $request->name,
                "image_url" => url("assets/images/fb/apps/1000/results/{$request->id}.jpg"),
                "title" => "",
                "description" => "",
                "data" => json_encode([
                    "car" => $car
                ])
            ]);
        }

        if ($fbAppResult) {

            if (isset($car)) {
                $imageUrl = thumbnail("fb/apps/1000/cars/$car", 1200, 630);
            } else {
                dd($fbAppResult->data);
                $data = json_decode($fbAppResult->data);
                $imageUrl = thumbnail("fb/apps/1000/cars/$data->car", 1200, 630);
            }

            return response()->json([
                "success" => true,
                "data" => [
                    "imageUrl" => $imageUrl,
                    "link" => url("/fb/apps/1000?userId=" . $fbAppResult->user_id),
                    "car" => "Lamborghini",
                    "result" => $fbAppResult
                ]
            ]);
        } else {
            return response()->json([
                "success" => false
            ]);
        }
    }

    private function calculateCar($id)
    {
        $digits = substr($id, -2);
        $arr = str_split($digits);
        $number = (int)$arr[0] + (int)$arr[1];
        switch ($number) {
            case 12:
                return 'lamborghini.jpg';
                break;
        }
    }
}