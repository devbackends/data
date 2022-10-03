<?php

namespace Devvly\FluidPayment\Http\Controllers\API;

use Devvly\FluidPayment\Http\Controllers\Controller;
use Devvly\FluidPayment\Models\FluidCustomer;
use Illuminate\Http\JsonResponse;

class FluidController extends Controller
{
    /**
     * @param int $sellerId
     * @return JsonResponse
     */
    public function getTokenizerinfo(): JsonResponse
    {


        return response()->json([
            'status' => true,
            'data' => [
                'public_key' => getenv('FLUID_PUBLIC_KEY'),
                'url' => config('services.2adata.gateway_url'),
            ],
        ]);
    }
}