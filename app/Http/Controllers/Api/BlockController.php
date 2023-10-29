<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BlockController extends Controller
{
    public function index()
    {
        $block = Block::all();
        return new BlockResource(true, 'Block data', $block);
    }

    public function store(Request $request)
    {
        if(Block::where('block_code', '=', $request->block_code)->count() > 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Block code already used"
                    ]
                ]
            ],400));
        }

        $validator = Validator::make($request->all(), [
            'block_code'   => 'required',
            'block_max'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $block = Block::create([
            'block_code' => $request->block_code,
            'block_max'  => $request->block_max,
            'block_status' => 'available'
        ]);

        return new BlockResource(true, 'Block added successfully', $block);
    }
}
