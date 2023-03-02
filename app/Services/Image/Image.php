<?php

namespace App\Services\Image;

class Image
{
    protected $image_folder;

    public function __construct()
    {
        $this->image_folder = public_path('cache');

        // Если нет папки - создаем
        if (!file_exists($this->image_folder)) {
            mkdir($this->image_folder, 0755);
        }
    }

    protected function checkWebPSupport()
    {
        return !empty($_SERVER['HTTP_ACCEPT'])
            && !empty($_SERVER['HTTP_USER_AGENT'])
            && (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false || strpos($_SERVER['HTTP_USER_AGENT'], ' Chrome/') !== false);
    }

    public function baseThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.base_thumb_width'),
            config('image.base_thumb_height'));
    }

    public function newsThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.news_thumb_width'),
            config('image.news_thumb_height'));
    }

    public function newsImage($url, $watermark = false)
    {
        return $this->convertImage(
            $url,
            config('image.news_image_width'),
            config('image.news_image_height'),
            $watermark
        );
    }

    public function firmwareThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.firmware_thumb_width'),
            config('image.firmware_thumb_height'));
    }

    public function dataCenterThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.data_center_thumb_width'),
            config('image.data_center_thumb_height'));
    }

    public function dataCenterImage($url, $watermark = false)
    {
        return $this->convertImage(
            $url,
            config('image.data_center_image_width'),
            config('image.data_center_image_height'),
            $watermark
        );
    }

    public function equipmentThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.equipment_thumb_width'),
            config('image.equipment_thumb_height'));
    }

    public function equipmentImage($url, $watermark = false)
    {
        return $this->convertImage(
            $url,
            config('image.equipment_image_width'),
            config('image.equipment_image_height'),
            $watermark,
            true
        );
    }

    public function wikiThumb($url)
    {
        return $this->convertImage(
            $url,
            config('image.wiki_thumb_width'),
            config('image.wiki_thumb_height'));
    }

    public function avatar($url)
    {
        return $this->convertImage(
            $url,
            200,
            200);
    }

    public function optimize($url, $width = null, $height = null, $watermark = false)
    {
        return $this->convertImage($url, $width, $height, $watermark);
    }

    protected function convertImage($url, $width = null, $height = null, $watermark = false, $not_crop_lesser = false)
    {
        if (preg_match('/\.svg/', $url)) {
            return asset($url);
        }

        if (preg_match('/storage/', $url)) {
            $image_path = storage_path(preg_replace('#\/?storage#', 'app/public', $url));
        } else {
            $image_path = public_path($url);
        }

        // Если нет картинки - показываем стандартную
        if (!file_exists($image_path) || empty($url)) {
            if (file_exists(public_path(config('image.no_image')))) {
                return $this->convertImage(config('image.no_image'), $width, $height);
            }
            return 'no_image';
        }

        if (!($width && $height)) {
            list($width, $height, $origin_size) = getimagesize($image_path);
        } elseif ($not_crop_lesser) {
            // Если оригинал меньше установленных размеров тогда не обрезаем
            $img_size = getimagesize($image_path);
            if ($img_size[0] < $width && $img_size[1] < $height) {
                $width = $img_size[0];
                $height = $img_size[1];
            }
        }

        $image_ext      = str_replace('jpg', 'jpeg', $this->checkWebPSupport() ? 'webp' : \File::extension($image_path));
        $image_name_ext = \File::name($image_path) . "-{$width}x{$height}." . $image_ext;
        $image_output   = $this->image_folder . '/' . $image_name_ext;
        $image_asset    = asset('cache/' . $image_name_ext);
        $watermark_path = public_path('default/img/watermark.png');

        // Если картинка уже создана - показываем из кэша
        if (file_exists($image_output)) {
            return $image_asset;
        }

        $image = new \claviska\SimpleImage();

        $processing = $image->fromFile($image_path);
        if (!isset($origin_size)) {
            $processing->thumbnail($width, $height);
            $processing->contrast(-5);
        }
        if ($watermark && file_exists($watermark_path)) {
            $watermark_image  = new \claviska\SimpleImage();
            $watermark_output = $watermark_image
                ->fromFile($watermark_path)
                ->resize($processing->getWidth() / 3.5);

            $processing->overlay($watermark_output, 'center', 0.8, 0, 0);
        }
        $processing->toFile($image_output, 'image/' . $image_ext, 80);

        return $image_asset;
    }
}
