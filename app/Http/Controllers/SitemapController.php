<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coin;
use App\Models\News;
use App\Models\Wiki;
use App\Models\Country;
use App\Models\Firmware;
use LaravelLocalization;
use App\Models\Equipment;
use App\Models\DataCenter;
use App\Models\WikiCategory;
use App\Models\FirmwareCategory;

class SitemapController extends Controller
{
    public function index()
    {
        $str = '';

        $setting = setting('sitemap');

        $str .= $this->createRowAllLang(route('home'));

        if ($setting['ratings']) {
            foreach (Coin::active()->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('ratings.show', $item->alias));
                }
            }
        }

        if ($setting['mining']) {
            $str .= $this->createRowAllLang(route('mining'));
        }

        if ($setting['firmwares']) {
            $str .= $this->createRowAllLang(route('firmwares'));
            foreach (FirmwareCategory::active()->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('firmwares.category', $item->alias));
                }
            }
            foreach (Firmware::active()->with('category')->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('firmwares.show', [$item->category->alias, $item->alias]));
                }
            }
        }

        if ($setting['data_centers']) {
            $str .= $this->createRowAllLang(route('data_centers'));
            foreach (Country::active()->get() as $item) {
                if (($item->getMeta('data_centers')['sitemap'] ?? true)) {
                    $str .= $this->createRowAllLang(route('data_centers.country', $item->alias));
                }
            }
            foreach (City::active()->get() as $item) {
                if (($item->getMeta('data_centers')['sitemap'] ?? true)) {
                    $str .= $this->createRowAllLang(route('data_centers.city', $item->alias));
                }
            }
            foreach (DataCenter::active()->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('data_centers.show', $item->alias));
                }
            }
        }

        if ($setting['equipments']) {
            $str .= $this->createRowAllLang(route('equipments'));
            foreach (Coin::active()->get() as $item) {
                if (($item->getMeta('equipments')['sitemap'] ?? true)) {
                    $str .= $this->createRowAllLang(route('equipments.coin', $item->alias));
                }
            }
            foreach (Equipment::active()->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('equipments.show', $item->alias));
                }
            }

            $str .= $this->createRowAllLang(route('crypto_calc'));
            foreach (Coin::active()->get() as $item) {
                if (($item->getMeta('crypto_calc')['sitemap'] ?? true)) {
                    $str .= $this->createRowAllLang(route('crypto_calc.coin', $item->alias));
                }
            }
        }

        if ($setting['wiki']) {
            $str .= $this->createRowAllLang(route('wiki'));
            foreach (WikiCategory::active()->get() as $item) {
                if ($item->sitemap) {
                    $str .= $this->createRowAllLang(route('wiki.category', $item->alias));
                }
            }
            foreach (Wiki::active()->get() as $item) {
                if ($item->sitemap) {
                    foreach (LaravelLocalization::getSupportedLocales() as $key => $lang) {
                        if (!empty($item->translate($key)->title)) {
                            $str .= $this->createRow(
                                LaravelLocalization::getLocalizedURL($key, route('wiki.show', $item->alias)),
                                $item->updated_at
                            );
                        }
                    }
                }
            }
        }

        if ($setting['contacts']) {
            $str .= $this->createRowAllLang(route('contacts'));
        }

        if ($setting['news']) {
            $str .= $this->createRowAllLang(route('news'));
            // foreach (NewsCategory::active()->get() as $item) {
            //     if ($item->sitemap) {
            //         $str .= $this->createRowAllLang(route('news.category', $item->alias));
            //     }
            // }
            foreach (News::active()->get() as $item) {
                if ($item->sitemap) {
                    foreach (LaravelLocalization::getSupportedLocales() as $key => $lang) {
                        if (!empty($item->translate($key)->title)) {
                            $str .= $this->createRow(
                                LaravelLocalization::getLocalizedURL($key, route('news.show', $item->alias)),
                                $item->updated_at
                            );
                        }
                    }
                }
            }
        }

        $sitemap = $this->createBody($str);

        abort_if(!isset($sitemap), 404);

        return response($sitemap)->header('Content-Type', 'text/xml');
    }

    public function createBody($body, $type = 'urlset')
    {
        $str = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $str .= '<' . $type . ' xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $str .= $body;
        $str .= '</' . $type . '>';

        return $str;
    }

    public function createRowAllLang($loc, $lastmod = null)
    {
        $time = $lastmod ? $lastmod : now();

        $str = '';

        foreach (LaravelLocalization::getSupportedLocales() as $key => $item) {
            $str .= $this->createRow(
                LaravelLocalization::getLocalizedURL($key, $loc),
                $time
            );
        }

        return $str;
    }

    public function createRow($loc, $lastmod = null)
    {
        $time = $lastmod ? $lastmod : now();

        return '<url>
                    <loc>' . $loc . '</loc>
                    <lastmod>' . $time->tz('GMT')->toAtomString() . '</lastmod>
                    <changefreq>weekly</changefreq>
                    <priority>1</priority>
                </url>';
    }
}
