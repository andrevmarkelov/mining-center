<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\Equipment;
use Meta;

class CryptoCalcController extends Controller
{
    public function index($coin = null)
    {
        if ($coin) {
            $coin = Coin::active()->where('alias', $coin)->firstOrFail();

            $metadata = $coin->getMeta('crypto_calc');

            Meta::setTitle($metadata[app()->getLocale()]['meta_title'] ?? '');
            Meta::setDescription($metadata[app()->getLocale()]['meta_description'] ?? '');
        }

        $equipments = Equipment::with('coin')->active()->when($coin, function ($query) use ($coin) {
            $query->where('coin_id', $coin->id);
        })->orderByDesc('id')->get();

        Meta::includePackages('datatables', 'fixedcolumns');

        return view('crypto_calc.index', compact('equipments', 'coin') + [
            'coins' => Coin::with('media')->active()->has('equipments')->get(),
            'page_title' => $metadata[app()->getLocale()]['meta_h1'] ?? '',
            'page_subtitle' => $metadata[app()->getLocale()]['subtitle'] ?? '',
            'page_description' => $metadata[app()->getLocale()]['description'] ?? '',
        ]);
    }
}
