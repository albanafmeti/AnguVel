<?php

namespace App\Http\Controllers\Api;

use App\FbAppResult;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

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

    private function app1000(Request $request)
    {
        $fbAppResult = FbAppResult::where("user_id", $request->id)->first();
        $exists = true;

        if (!$fbAppResult) {

            $exists = false;
            $car = (object)$this->calculateCar($request->id);

            $userProfilePic = Image::make($request->picture['data']['url'])->fit(250, 250);
            $userProfilePic = $userProfilePic->rectangle(0, 0, 250, 250, function ($draw) {
                $draw->border(5, '#292b2c');
            });

            $carPic = Image::make(public_path("assets/images/fb/apps/1000/cars/$car->file"))->fit(1200, 630);
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
            case 0:
                return [
                    "file" => '2013_fiat_500l-wide.jpg',
                    "name" => "Fiat 500L"
                ];
                break;
            case 1:
                return [
                    "file" => '2013_opel_adam-wide.jpg',
                    "name" => "Opel Adam"
                ];
                break;
            case 2:
                return [
                    "file" => '2015_hyundai_genesis_coupe-wide.jpg',
                    "name" => "Hyundai Genesis Coupe"
                ];
                break;
            case 3:
                return [
                    "file" => '2017_ferrari_812_superfast_3-HD.jpg',
                    "name" => "Ferrari 812"
                ];
                break;
            case 4:
                return [
                    "file" => '2018_audi_a7_sportback_quattro_4k-HD.jpg',
                    "name" => "AUDI A7 Sportback"
                ];
                break;
            case 5:
                return [
                    "file" => '2018_bmw_4_series_coupe_4k-HD.jpg',
                    "name" => "BMV 4 Series Coupe"
                ];
                break;
            case 6:
                return [
                    "file" => '2018_bmw_i3s_4_4k-HD.jpg',
                    "name" => "BMW I3S"
                ];
                break;
            case 7:
                return [
                    "file" => '2018_bmw_m4_cs_2-HD.jpg',
                    "name" => "BMW M4"
                ];
                break;
            case 8:
                return [
                    "file" => '2018_mercedes_amg_gt_c_edition_50_4k-HD.jpg',
                    "name" => "MERCEDES AMG"
                ];
                break;
            case 9:
                return [
                    "file" => '2018_renault_megane_rs_4k_4-HD.jpg',
                    "name" => "RENAULT MEGANE"
                ];
                break;
            case 10:
                return [
                    "file" => '2018_toyota_land_cruiser_luxury_suv_4k-HD.jpg',
                    "name" => "TOYOTA LAND CRUISER"
                ];
                break;
            case 11:
                return [
                    "file" => '2018_volkswagen_t_roc_4motion_4k-HD.jpg',
                    "name" => "VOLKSWAGEN T ROC"
                ];
                break;
            case 12:
                return [
                    "file" => '2018_volvo_polestar_1_4k_2-HD.jpg',
                    "name" => "VOLVO POLESTAR"
                ];
                break;
            case 13:
                return [
                    "file" => '2019_land_rover_discovery_svx_2-HD.jpg',
                    "name" => "LAND ROVER DISCOVERY"
                ];
                break;
            case 14:
                return [
                    "file" => 'arden_aj24_jaguar_xe_2017_4k_2-HD.jpg',
                    "name" => "JAGUAR XE"
                ];
                break;
            case 15:
                return [
                    "file" => 'bugatti_chiron_most_expensive_car-HD.jpg',
                    "name" => "BUGATTI CHIRON"
                ];
                break;
            case 16:
                return [
                    "file" => 'ford_edge_2016_4k-HD.jpg',
                    "name" => "FORD EDGE"
                ];
                break;
            case 17:
                return [
                    "file" => 'lamborghini_aventador_s_2017_4k_3-HD.jpg',
                    "name" => "LAMBORGHINI AVENTADOR"
                ];
                break;
            case 18:
                return [
                    "file" => 'mazda_vision_coupe_concept-HD.jpg',
                    "name" => "MAZDA VISION"
                ];
                break;
            default:
                return [
                    "file" => 'lamborghini_aventador_s_2017_4k_3-HD.jpg',
                    "name" => "LAMBORGHINI AVENTADOR"
                ];
        }
    }
}