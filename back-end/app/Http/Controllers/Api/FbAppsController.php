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
        $exists = true;

        if (!$fbAppResult) {

            $exists = false;
            $car = (object)$this->calculateCar($request->id);

            $userProfilePic = Image::make($request->picture['data']['url'])->fit(250, 250);


            $userProfilePic = $userProfilePic->rectangle(0, 0, 250, 250, function ($draw) {
                //$draw->background('rgba(255, 255, 255, 0.5)');
                $draw->border(5, '#292b2c');
            });


            $carPic = Image::make(public_path("assets/images/fb/apps/1000/cars/$car->file"))->fit(1200, 630);

            // use callback to define details
            $carPic = $carPic->text($car->name, 585, 50, function ($font) {
                $font->file(public_path('/fonts/Vollkorn_SC/VollkornSC-Regular.ttf'));
                $font->size(50);
                $font->color('#fff');
                $font->align('center');
                $font->valign('top');
            });

            $canvas = Image::canvas(1200, 630);
            $canvas->insert($carPic, 'center');
            $canvas->insert($userProfilePic, 'top-left');
            $canvas->encode('jpg', 100)->save(public_path("assets/images/fb/apps/1000/results/{$request->id}.jpg"));
            $fbAppResult = FbAppResult::create([
                "app_id" => "1000",
                "user_id" => $request->id,
                "user_name" => $request->name,
                "image_url" => url("assets/images/fb/apps/1000/results/{$request->id}.jpg"),
                "title" => "Çfare automjeti do te keni pas 10 vitesh?",
                "description" => "Zbuloni makinen tuaj te ardhshme. Kliko linkun, provoje dhe ti!",
                "data" => json_encode([
                    "car" => $car
                ])
            ]);
        }

        if ($fbAppResult) {

            if (isset($car)) {
                $imageUrl = thumbnail("fb/apps/1000/cars/$car->file", 1200, 630);
            } else {
                $data = json_decode($fbAppResult->data);
                $imageUrl = thumbnail("fb/apps/1000/cars/{$data->car->file}", 1200, 630);
            }

            return response()->json([
                "success" => true,
                "data" => [
                    "imageUrl" => $imageUrl,
                    "link" => "http://terejat.al/fb/apps/1000?userId=" . $fbAppResult->user_id,
                    "car" => "Lamborghini",
                    "exists" => $exists,
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
                return [
                    "file" => 'lamborghini.jpg',
                    "name" => "Lamborghini"
                ];
                break;
            default:
                return [
                    "file" => 'lamborghini.jpg',
                    "name" => "Lamborghini"
                ];
        }
    }
}