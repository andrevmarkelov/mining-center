@extends('layouts.admin')

@section('content')
    @component('admin.components.breadcrumb')
        @slot('title', 'Настройки сайта')
    @endcomponent

    @php
        $current_lang = app()->getLocale();
        $langs = LaravelLocalization::getSupportedLocales();

        function activiteTab($key)
        {
            return key(LaravelLocalization::getSupportedLocales()) == $key ? 'active' : '';
        }
    @endphp

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.settings.update') }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">

                        <div class="card card-outline card-outline-tabs mb-0">
                            <div class="card-header px-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-header" data-toggle="tab">Основные</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-home" data-toggle="tab">Главная</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-mining" data-toggle="tab">Облачный майнинг</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-firmwares" data-toggle="tab">Прошивки</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-contacts" data-toggle="tab">Контакты</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-seo" data-toggle="tab">SEO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-codes" data-toggle="tab">CSS/JS</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-header">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Шапка</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-binance_link">Binance ссылка</label>
                                            <input name="setting[binance_link]" value="{{ $setting['binance_link'] ?? '' }}"
                                                type="url" id="input-binance_link" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-btc_halving">BTC Halving News</label>
                                            <select name="setting[news_add_categories][btc_halving]" id="input-btc_halving" class="form-control js-news-categories" data-placeholder="Выбрать значение">
                                                @php
                                                    $category_id = setting('news_add_categories')['btc_halving'] ?? null;
                                                @endphp
                                                @if(!empty($item = $news_add_categories->find($category_id)))
                                                    <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="input-menu_coins">Список монет в меню "Рейтинг пулов"</label>
                                            <select name="setting[menu_coins][]" id="input-menu_coins" class="form-control" multiple data-placeholder="Выбрать значение">
                                                @foreach ($menu_coins as $item)
                                                    <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Новости</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-news_hot_topic">Hot topic</label>
                                            <select name="setting[news_hot_topic]" id="input-news_hot_topic"
                                                class="form-control" data-placeholder="Выбрать значение">
                                                <option value="">Выберите значение</option>
                                                @if ($news_hot_topic)
                                                    <option selected value="{{ $news_hot_topic->id }}">{{ $news_hot_topic->title }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Сопоставление категорий</label>
                                            <table class="table table-striped">
                                                <tbody>
                                                    @foreach ([
                                                        'reviews' => 'Обзоры',
                                                        'reports' => 'Репортажи',
                                                        'legal' => 'Законы',
                                                        'events' => 'События',
                                                        'people' => 'Люди'
                                                    ] as $key => $title)
                                                    <tr>
                                                        <th style="width: 25%;">{{ $title }}</th>
                                                        <td>
                                                            <select name="setting[news_categories][{{ $key }}]" class="form-control js-news-categories" data-placeholder="Выбрать значение">
                                                                @php
                                                                    $category_id = setting('news_categories')[$key] ?? null;
                                                                @endphp
                                                                @if(!empty($item = $news_categories->find($category_id)))
                                                                    <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                    @foreach (['investment' => 'Инвестиции'] as $key => $title)
                                                    <tr>
                                                        <th style="width: 25%;">{{ $title }}</th>
                                                        <td>
                                                            <select name="setting[news_add_categories][{{ $key }}]" class="form-control js-news-categories" data-placeholder="Выбрать значение">
                                                                @php
                                                                    $category_id = setting('news_add_categories')[$key] ?? null;
                                                                @endphp
                                                                @if(!empty($item = $news_add_categories->find($category_id)))
                                                                    <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Оборудование</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-home_equipments">Доходность майнеров на главной</label>
                                            <select name="setting[home_equipments][]" id="input-home_equipments" class="form-control" multiple data-placeholder="Выбрать значение">
                                                @foreach ($home_equipments as $item)
                                                    <option value="{{ $item->id }}" selected>{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="input-featured_pools">Рекомендуемые пулы</label>
                                            <select name="setting[featured_pools][]" id="input-featured_pools"
                                                class="form-control js-select2" multiple
                                                data-placeholder="Выбрать значение">
                                                @foreach ($ratings as $rating_id => $rating)
                                                    <option value="{{ $rating_id }}"
                                                        @if (in_array($rating_id, $setting['featured_pools'] ?? [])) selected @endif>
                                                        {{ $rating }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input name="setting[coin_calc_show]" value="0" type="hidden" checked>
                                                <input name="setting[coin_calc_show]" value="1"
                                                    class="custom-control-input" type="checkbox" id="input-coin_calc_show"
                                                    @if ($setting['coin_calc_show'] ?? false) checked @endif>
                                                <label for="input-coin_calc_show" class="custom-control-label">Отображать
                                                    "Расчет доходности"</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-home">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Главная</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($langs as $key => $item)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ activiteTab($key) }}"
                                                        href="#tab-home-{{ $key }}"
                                                        data-toggle="tab">{{ $item['native'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content pt-3">
                                            @foreach ($langs as $key => $item)
                                                {{ app()->setLocale($key) }}
                                                <div class="tab-pane {{ activiteTab($key) }}"
                                                    id="tab-home-{{ $key }}">
                                                    <div class="form-group">
                                                        <label for="{{ $key }}_input-home_top_pools">Топ пулов
                                                            ({{ $key }})
                                                        </label>
                                                        <textarea name="setting[{{ $key }}][home_top_pools]" id="{{ $key }}_input-home_top_pools"
                                                            class="form-control js-summernote" data-height="150">{{ $setting[$key]['home_top_pools'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                            {{ app()->setLocale($current_lang) }}
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-mining">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Облачный майнинг</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($langs as $key => $item)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ activiteTab($key) }}"
                                                        href="#tab-mining-{{ $key }}"
                                                        data-toggle="tab">{{ $item['native'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content pt-3">
                                            @foreach ($langs as $key => $item)
                                                {{ app()->setLocale($key) }}
                                                <div class="tab-pane {{ activiteTab($key) }}"
                                                    id="tab-mining-{{ $key }}">
                                                    <div class="form-group">
                                                        <label>Преимущества ({{ $key }})</label>
                                                        @for ($i = 1; $i <= 4; $i++)
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-append">
                                                                    <span
                                                                        class="input-group-text">{{ $i }}</span>
                                                                </div>
                                                                <div class="col p-0">
                                                                    <input placeholder="Название ({{ $key }})"
                                                                        name="setting[{{ $key }}][mining_advantage{{ $i }}_title]"
                                                                        value="{{ $setting[$key]['mining_advantage' . $i . '_title'] ?? '' }}"
                                                                        class="form-control">
                                                                    <textarea placeholder="Описание ({{ $key }})"
                                                                        name="setting[{{ $key }}][mining_advantage{{ $i }}_text]" class="form-control">{{ $setting[$key]['mining_advantage' . $i . '_text'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            for="{{ $key }}_input-mining_services_text">Сервисы
                                                            описание ({{ $key }})</label>
                                                        <textarea name="setting[{{ $key }}][mining_services_text]"
                                                            id="{{ $key }}_input-mining_services_text" class="form-control js-summernote" data-height="150">{{ $setting[$key]['mining_services_text'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                            {{ app()->setLocale($current_lang) }}
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-firmwares">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Прошивки</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($langs as $key => $item)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ activiteTab($key) }}"
                                                        href="#tab-firmwares-{{ $key }}"
                                                        data-toggle="tab">{{ $item['native'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content pt-3">
                                            @foreach ($langs as $key => $item)
                                                {{ app()->setLocale($key) }}
                                                <div class="tab-pane {{ activiteTab($key) }}"
                                                    id="tab-firmwares-{{ $key }}">
                                                    <div class="form-group">
                                                        <label for="{{ $key }}_input-firmwares_top_text">Текст
                                                            вверху страницы ({{ $key }})</label>
                                                        <textarea name="setting[{{ $key }}][firmwares_top_text]" id="{{ $key }}_input-firmwares_top_text"
                                                            class="form-control js-summernote" data-height="150">{{ $setting[$key]['firmwares_top_text'] ?? '' }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            for="{{ $key }}_input-firmwares_advantages_desc">Преимущества
                                                            описание ({{ $key }})</label>
                                                        <textarea name="setting[{{ $key }}][firmwares_advantages_desc]"
                                                            id="{{ $key }}_input-firmwares_advantages_desc" class="form-control">{{ $setting[$key]['firmwares_advantages_desc'] ?? '' }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Преимущества ({{ $key }})</label>
                                                        @for ($i = 1; $i <= 6; $i++)
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-append">
                                                                    <span
                                                                        class="input-group-text">{{ $i }}</span>
                                                                </div>
                                                                <textarea placeholder="Описание ({{ $key }})"
                                                                    name="setting[{{ $key }}][firmwares_advantage{{ $i }}_text]" class="form-control">{{ $setting[$key]['firmwares_advantage' . $i . '_text'] ?? '' }}</textarea>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="{{ $key }}_input-firmwares_bottom_text">Текст
                                                            внизу к прошивкам ({{ $key }})</label>
                                                        <textarea name="setting[{{ $key }}][firmwares_bottom_text]"
                                                            id="{{ $key }}_input-firmwares_bottom_text" class="form-control js-summernote" data-height="150">{{ $setting[$key]['firmwares_bottom_text'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                            {{ app()->setLocale($current_lang) }}
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-contacts">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Контакты</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-email">Email</label>
                                            <input name="setting[email]" value="{{ $setting['email'] ?? '' }}"
                                                type="email" id="input-email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-telegram">Телеграм</label>
                                            <input name="setting[telegram]" value="{{ $setting['telegram'] ?? '' }}"
                                                type="text" id="input-telegram" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-phone1">Телефон</label>
                                            <input name="setting[phone][0]" value="{{ $setting['phone'][0] ?? '' }}"
                                                type="text" id="input-phone1" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-phone2">Телефон доп.</label>
                                            <input name="setting[phone][1]" value="{{ $setting['phone'][1] ?? '' }}"
                                                type="text" id="input-phone2" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Адрес</label>
                                            @foreach ($langs as $key => $item)
                                                {{ app()->setLocale($key) }}
                                                <div class="input-group mb-3">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="{{ $item['flag_class'] }}"></i>
                                                        </span>
                                                    </div>
                                                    <input name="setting[{{ $key }}][address]"
                                                        value="{{ $setting[$key]['address'] ?? '' }}" type="text"
                                                        class="form-control">
                                                </div>
                                            @endforeach
                                            {{ app()->setLocale($current_lang) }}
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Соцсети</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-twitter">Twitter</label>
                                            <input name="setting[twitter]" value="{{ $setting['twitter'] ?? '' }}"
                                                type="url" id="input-twitter" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-youtube">Youtube</label>
                                            <input name="setting[youtube]" value="{{ $setting['youtube'] ?? '' }}"
                                                type="url" id="input-youtube" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-facebook">Facebook</label>
                                            <input name="setting[facebook]" value="{{ $setting['facebook'] ?? '' }}"
                                                type="url" id="input-facebook" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-instagram">Instagram</label>
                                            <input name="setting[instagram]" value="{{ $setting['instagram'] ?? '' }}"
                                                type="url" id="input-instagram" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="input-discord">Discord</label>
                                            <input name="setting[discord]" value="{{ $setting['discord'] ?? '' }}"
                                                type="url" id="input-discord" class="form-control">
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-seo">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Переадресация</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="input-redirect">Введите значение, пример old-url|new-url</label>
                                            <textarea name="setting[redirect]" id="input-redirect" rows="10" class="form-control">{{ $setting['redirect'] ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Robots.txt</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <textarea name="robots" rows="10" class="form-control">{{ $robots ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Sitemap (отображать разделы)</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-row">
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-ratings">Рейтинг пулов</label>
                                                <div>
                                                    <input name="setting[sitemap][ratings]" value="0" type="hidden"
                                                        checked>
                                                    <input name="setting[sitemap][ratings]" value="1"
                                                        type="checkbox" id="input-sitemap-ratings" data-bootstrap-switch
                                                        @if ($setting['sitemap']['ratings'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-mining">Облачный майнинг</label>
                                                <div>
                                                    <input name="setting[sitemap][mining]" value="0" type="hidden"
                                                        checked>
                                                    <input name="setting[sitemap][mining]" value="1" type="checkbox"
                                                        id="input-sitemap-mining" data-bootstrap-switch
                                                        @if ($setting['sitemap']['mining'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-firmwares">Прошивки</label>
                                                <div>
                                                    <input name="setting[sitemap][firmwares]" value="0"
                                                        type="hidden" checked>
                                                    <input name="setting[sitemap][firmwares]" value="1"
                                                        type="checkbox" id="input-sitemap-firmwares" data-bootstrap-switch
                                                        @if ($setting['sitemap']['firmwares'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-data_centers">Дата центры</label>
                                                <div>
                                                    <input name="setting[sitemap][data_centers]" value="0"
                                                        type="hidden" checked>
                                                    <input name="setting[sitemap][data_centers]" value="1"
                                                        type="checkbox" id="input-sitemap-data_centers"
                                                        data-bootstrap-switch
                                                        @if ($setting['sitemap']['data_centers'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-equipments">Оборудование</label>
                                                <div>
                                                    <input name="setting[sitemap][equipments]" value="0"
                                                        type="hidden" checked>
                                                    <input name="setting[sitemap][equipments]" value="1"
                                                        type="checkbox" id="input-sitemap-equipments"
                                                        data-bootstrap-switch
                                                        @if ($setting['sitemap']['equipments'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-wiki">WIKI</label>
                                                <div>
                                                    <input name="setting[sitemap][wiki]" value="0" type="hidden"
                                                        checked>
                                                    <input name="setting[sitemap][wiki]" value="1" type="checkbox"
                                                        id="input-sitemap-wiki" data-bootstrap-switch
                                                        @if ($setting['sitemap']['wiki'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-contacts">Контакты</label>
                                                <div>
                                                    <input name="setting[sitemap][contacts]" value="0"
                                                        type="hidden" checked>
                                                    <input name="setting[sitemap][contacts]" value="1"
                                                        type="checkbox" id="input-sitemap-contacts" data-bootstrap-switch
                                                        @if ($setting['sitemap']['contacts'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="input-sitemap-news">Новости</label>
                                                <div>
                                                    <input name="setting[sitemap][news]" value="0" type="hidden"
                                                        checked>
                                                    <input name="setting[sitemap][news]" value="1" type="checkbox"
                                                        id="input-sitemap-news" data-bootstrap-switch
                                                        @if ($setting['sitemap']['news'] ?? true) checked @endif>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-codes">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Дополнительные стили CSS</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <textarea name="setting[style_head]" rows="8" class="form-control">{{ $setting['style_head'] ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Scripts в head</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <textarea name="setting[code_head]" rows="8" class="form-control">{{ $setting['code_head'] ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Scripts в подвале</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($langs as $key => $item)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ activiteTab($key) }}"
                                                        href="#tab-code-footer-{{ $key }}"
                                                        data-toggle="tab">{{ $item['native'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content pt-3">
                                            @foreach ($langs as $key => $item)
                                                {{ app()->setLocale($key) }}
                                                <div class="tab-pane {{ activiteTab($key) }}"
                                                    id="tab-code-footer-{{ $key }}">
                                                    <div class="form-group">
                                                        <textarea name="setting[{{ $key }}][code_footer]" rows="8" class="form-control">{{ $setting[$key]['code_footer'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                            {{ app()->setLocale($current_lang) }}
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">JSON настройки анимации на сайте, <a target="_blank"
                                                href="https://vincentgarreau.com/particles.js/">пример</a></h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group">
                                            <textarea name="particles" rows="10" class="form-control">{{ $particles ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script')
    <script>
        function select2Ajax(selector, route) {
            $(selector).select2({
                // theme: 'bootstrap4',
                allowClear: true,
                minimumInputLength: 2,
                ajax: {
                    url: route,
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.title
                                };
                            })
                        };
                    },
                }
            });
        }

        select2Ajax('#input-home_equipments', '{{ route('api.equipments.index') }}');
        select2Ajax('#input-news_hot_topic', '{{ route('api.news.index') }}');
        select2Ajax('#input-menu_coins', '{{ route('api.coins.index') }}');
        select2Ajax('.js-news-categories', '{{ route('api.news_categories.index') }}');
    </script>
@endsection
