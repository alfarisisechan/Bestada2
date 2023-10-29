<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slot;
use App\Http\Resources\SlotResource;
use App\Models\Block;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SlotController extends Controller
{
    public function index()
    {
        $slot = Slot::all();
        return new SlotResource(true, 'Slot data', $slot);
    }

    public function store(Request $request)
    {
        if(Slot::where('vehicle_number', '=', strtoupper($request->vehicle_number))->where('status', '=', 'in')->count() > 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "You're parked!"
                    ]
                ]
            ],400));
        }
        $validator = Validator::make($request->all(), [
            'vehicle_number'   => 'required',
            'block_code'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $slot = Slot::where('block_code', '=', $request->block_code)->where('status', '=', 'in')->count();
        if(Block::where('block_code', '=', $request->block_code)->where('block_max', '<=', $slot)->count() > 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Parking Area Full"
                    ]
                ]
            ],400));
        }
        $create = Slot::create([
            'vehicle_number' => strtoupper($request->vehicle_number),
            'block_code'  => $request->block_code,
            'status' => 'in',
            'time_in' => Carbon::now()->toDateTimeString(),
            'time_out' => NULL
        ]);
        $slots = Slot::where('block_code', '=', $request->block_code)->where('status', '=', 'in')->count();
        if(Block::where('block_code', '=', $request->block_code)->where('block_max', '<=', $slots)->count() > 0){
            Block::where('block_code', $request->block_code)->update([
                'block_status'     => 'full',
            ]);
        }
        return new SlotResource(true, 'Parking Success', $create);
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::find($id);
        $slots = Slot::where('block_code', $request->block_code)->where('vehicle_number', strtoupper($request->vehice_number))->update([
            'status'     => 'out',
            'time_out'   => Carbon::now()->toDateTimeString()
        ]);
        if(Block::where('block_code', '=', $request->block_code)->where('block_max', '>', $slot)->count() > 0)
        {
            Block::where('block_code', $request->block_code)->update([
                'block_status'     => 'available',
            ]); 
        }
        return new SlotResource(true, 'Thank you', $slots);
    }
}
