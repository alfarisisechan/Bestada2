<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicle = Vehicle::all();
        return new VehicleResource(true, 'Vehicle Number', $vehicle);
    }

    public function store(Request $request)
    {
        if(Vehicle::where('vehicle_number', '=', strtoupper($request->vehicle_number))->count() > 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Vehicle number already used"
                    ]
                ]
            ],400));
        }

        $validator = Validator::make($request->all(), [
            'vehicle_number'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vehicle = Vehicle::create([
            'vehicle_number'   => strtoupper($request->vehicle_number)
        ]);

        return new VehicleResource(true, 'Vehicle Number Registered', $vehicle);
    }
}
