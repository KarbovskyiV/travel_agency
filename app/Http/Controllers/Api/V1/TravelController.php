<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{
    public function index()
    {
        $travels = Travel::query()->where('is_public', '=', 1)->paginate();

        return TravelResource::collection($travels);
    }
}