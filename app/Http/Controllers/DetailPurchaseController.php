<?php

namespace App\Http\Controllers;

use App\Models\DetailPurchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DetailPurchaseController extends Controller
{
    /**
     * Display the specified purchase detail item.
     */
    public function show(Request $request, DetailPurchase $detailPurchase): JsonResponse
    {
        return response()->json(
            $detailPurchase->loadMissing(['purchase', 'product'])
        );
    }
}
