<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Wiki;

class RssFeedController extends Controller
{
    public function yandex()
    {
        return $this->createStructure('Yandex');
    }

    public function google()
    {
        return $this->createStructure('Google');
    }

    protected function createStructure($type)
    {
        $str = '';

        foreach (News::with('user', 'media')->active()->latest()->take(30)->get() as $item) {
            if ($item->title) {
                $str .= $this->{'create' . $type . 'Row'}($item, 'news', app()->getLocale() == 'ru' ? 'Новости' : 'News');
            }
        }

        foreach (Wiki::with('user', 'media')->active()->latest()->take(30)->get() as $item) {
            if ($item->title) {
                $str .= $this->{'create' . $type . 'Row'}($item, 'wiki', "Wiki");
            }
        }

        $rss = $this->{'create' . $type . 'Body'}($str, app()->getLocale() == 'ru' ? 'Новости на' : 'News on');

        return response($rss)->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    protected function createYandexBody($str, $title)
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <rss xmlns:yandex="http://news.yandex.ru"
                     xmlns:media="http://search.yahoo.com/mrss/"
                     version="2.0">
                    <channel>
                        <atom:link href="' . route('rss_feed') . '" rel="self" type="application/rss+xml" />
                        <title>' . $title . ' ' . env('APP_NAME') . '</title>
                        <link>' . route('home') . '</link>
                        <description>' . env('APP_NAME') . ' RSS feed</description>
                        <language>' . app()->getLocale() . '</language>
                        <pubDate>' . now()->format('D, d M Y H:i:s') . ' +0300</pubDate>
                        <ttl>300</ttl>
                        <image>
                            <url>' . asset('default/img/logo.png') . '</url>
                            <title>' . $title . ' ' . env('APP_NAME') . '</title>
                            <link>' . route('home') . '</link>
                        </image>
                        ' . $str . '
                    </channel>
                </rss>';
    }

    protected function createYandexRow($item, $type, $category)
    {
        $str = '<item turbo="true">
                    <title>' . $item->title . '</title>';

        if (isset($item->user)) {
            $str .= PHP_EOL . '<author>' . $item->user->name . '</author>';
        }

        $str .= PHP_EOL . '<category>' . $category . '</category>
                    <link>' . route($type . '.show', $item->alias) . '</link>
                    <description>' . $item->meta_description . '</description>
                    <pubDate>' . $item->created_at->format('D, d M Y H:i:s') . ' +0300</pubDate>
                    <guid>' . route($type . '.show', $item->alias) . '</guid>';

        if ($item->image) {
            $str .= '<enclosure url="' . asset($item->image) . '" type="image/' . $item->getFirstMedia('image')->extension . '" length="0"/>';
        }

        $description = str_replace('/storage/', env('APP_URL') . '/storage/', $item->description);

        return $str . '<turbo:extendedHtml>true</turbo:extendedHtml>
                       <yandex:full-text><![CDATA[' . $description . ']]></yandex:full-text>
                       </item>';
    }

    protected function createGoogleBody($str, $title)
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
                    <channel>
                        <generator>NFE/5.0</generator>
                        <atom:link href="' . route('rss_google_feed') . '" rel="self" type="application/rss+xml" />
                        <title>' . $title . ' ' . env('APP_NAME') . '</title>
                        <link>' . route('home') . '</link>
                        <description>' . env('APP_NAME') . ' RSS feed</description>
                        <language>' . app()->getLocale() . '</language>
                        <pubDate>' . now()->format('D, d M Y H:i:s') . ' +0300</pubDate>
                        <image>
                            <url>' . asset('default/img/logo.png') . '</url>
                            <title>' . $title . ' ' . env('APP_NAME') . '</title>
                            <link>' . route('home') . '</link>
                        </image>
                        ' . $str . '
                    </channel>
                </rss>';
    }

    protected function createGoogleRow($item, $type, $category)
    {
        $str = '<item>
                    <title>' . $item->title . '</title>';

        $image_str = '';

        if ($item->image) {
            $image_str .= '<img src="' . asset($item->image) . '" alt=""/>';
        }

        $description = str_replace('/storage/', env('APP_URL') . '/storage/', $item->description);

        $str .= PHP_EOL . '<category>' . $category . '</category>
                    <link>' . route($type . '.show', $item->alias) . '</link>
                    <description><![CDATA[' . $image_str . $description . ']]></description>
                    <pubDate>' . $item->created_at->format('D, d M Y H:i:s') . ' +0300</pubDate>
                    <guid isPermaLink="false">' . route($type . '.show', $item->alias) . '</guid>
                    <source url="' . env('APP_URL') . '">' . env('APP_NAME') . '</source>
                </item>';

        return $str;
    }
}
