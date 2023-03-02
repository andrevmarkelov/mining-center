<?php

namespace App\ViewModels;

use App\Models\Equipment;

class EquipmentViewModel
{
    public static function home()
    {
        return Equipment::active()->with('coin')->whereIn('id', setting('home_equipments', []))->get();
    }
}
