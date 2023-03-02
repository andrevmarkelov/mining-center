<?php

namespace App\View\Components;

use App\Models\Coin;
use Illuminate\View\Component;

class PriceSchedule extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.price_schedule', [
            'coins' => Coin::with('media')->active()->whereNotNull('profit_per_unit')->take(5)->get()
        ]);
    }
}
