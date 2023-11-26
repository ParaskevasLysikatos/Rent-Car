<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{

    public function index($lng, $id)
    {
        $qrcodes = [];
        $car = Vehicle::find($id);
        $url = url()->route('scanner_redirect', ['car' => $car->id]);
        $url = str_replace("http://", "https://", $url);
        $img = QrCode::size(200)->generate($url);
        array_push($qrcodes, ['img' => $img, 'plate' => str_replace(' ', '', $car->getPlate()->licence_plate)]);
        return view('qrcodes.print', compact('qrcodes'));
    }

    public function cars()
    {
        $cars = Vehicle::select('id')->get();
        $url = url()->route('scanner_redirect');
        $url = str_replace("http://", "https://", $url);
        $qrcodes = [];

        foreach ($cars as $car) {
            $img = QrCode::size(200)->generate($url . "?car=" . $car->id);
            array_push($qrcodes, ['img' => $img, 'plate' => str_replace(' ', '', $car->getPlate()->licence_plate)]);
        }

        return view('qrcodes.print', compact('qrcodes'));
    }

}
