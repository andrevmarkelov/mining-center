<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Setting;
use App\Models\News;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Jobs\ClearNewsCacheJob;
use App\Http\Controllers\Controller;
use App\Jobs\ClearNewsCategoryCacheJob;
use App\Models\Coin;
use App\Models\Equipment;
use App\ViewModels\EquipmentViewModel;
use App\ViewModels\NewsCategoryViewModel;

class SettingController extends Controller
{
    public $robots;
    public $particles;

    public function __construct()
    {
        $this->robots = public_path('robots.txt');
        $this->particles = public_path('default/libs/particles/particles.json');
    }

    public function edit()
    {
        abort_if(Gate::denies('setting_edit'), 403, '403 Forbidden');

        if (!is_file($this->robots)) {
            touch($this->robots, strtotime('-1 days'));
        }

        if (!is_file($this->particles)) {
            touch($this->particles, strtotime('-1 days'));
        }

        return view('admin.settings.edit', [
            'news_hot_topic' => News::active()->find(setting('news_hot_topic')),
            'news_categories' => NewsCategoryViewModel::primary(),
            'news_add_categories' => NewsCategoryViewModel::additional(),
            'home_equipments' => EquipmentViewModel::home(),
            'menu_coins' => Coin::active()->whereIn('id', setting('menu_coins', []))->get(),
            'setting' => Setting::all(),
            'robots' => file_get_contents($this->robots),
            'particles' => file_get_contents($this->particles),
            'ratings' => Rating::active()->pluck('title', 'id'),
        ]);
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('setting_edit'), 403, '403 Forbidden');

        $setting = $request->input('setting');

        foreach ($setting as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        dispatch(new ClearNewsCategoryCacheJob);
        dispatch(new ClearNewsCacheJob);

        file_put_contents($this->robots, $request->input('robots'));
        file_put_contents($this->particles, $request->input('particles'));

        return response()->json([
            'success' => 'Настройки успешно сохранены.',
        ]);
    }
}
