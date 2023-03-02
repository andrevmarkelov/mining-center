<?php

namespace App\Http\Controllers;

use App\Models\Mining;
use Butschster\Head\Facades\Meta;

class MiningController extends Controller
{
    public function index()
    {
        Meta::includePackages('owl-carousel');

        return view('mining.index', [
            'mining' => Mining::with('media')->active()->orderByDesc('id')->get()
        ]);
    }
}
