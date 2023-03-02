<?php

namespace App\Http\Controllers\Api;

use App\Models\Equipment;
use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::active()->when($keyword = request()->input('q'), function($query) use ($keyword) {
            $query->whereTranslationLike('title', "%{$keyword}%", app()->getLocale());
        })->latest()->get();

        return EquipmentResource::collection($equipments);
    }
}
