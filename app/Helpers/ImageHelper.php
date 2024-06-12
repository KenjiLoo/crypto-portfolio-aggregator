<?php

namespace App\Helpers;

class ImageHelper
{
    const SIZES_BASE_WIDTH = 1920;
    const SIZES_BASE_HEIGHT = 1080;
    const SIZES_XS_WIDTH = 96;
    const SIZES_XS_HEIGHT = 54;
    const SIZES_SM_WIDTH = 384;
    const SIZES_SM_HEIGHT = 216;
    const SIZES_MD_WIDTH = 960;
    const SIZES_MD_HEIGHT = 540;
    const SIZES_LG_WIDTH = 1440;
    const SIZES_LG_HEIGHT = 810;
    const SIZES_XL_WIDTH = 1920;
    const SIZES_XL_HEIGHT = 1080;

    /**
     *
     */
    public static function resizeXs($img, $enlarge = true)
    {
        $width = $img->width();
        $height = $img->height();

        if ($height > $width) {
            if (!$enlarge && $height <= static::SIZES_XS_HEIGHT) {
                return $img;
            }

            return static::resizeImage($img, null, static::SIZES_XS_HEIGHT);
        } else {
            if (!$enlarge && $width <= static::SIZES_XS_WIDTH) {
                return $img;
            }

            return static::resizeImage($img, static::SIZES_XS_WIDTH, null);
        }
    }

    /**
     *
     */
    public static function resizeSm($img, $enlarge = true)
    {
        $width = $img->width();
        $height = $img->height();

        if ($height > $width) {
            if (!$enlarge && $height <= static::SIZES_SM_HEIGHT) {
                return $img;
            }

            return static::resizeImage($img, null, static::SIZES_SM_HEIGHT);
        } else {
            if (!$enlarge && $width <= static::SIZES_SM_WIDTH) {
                return $img;
            }

            return static::resizeImage($img, static::SIZES_SM_WIDTH, null);
        }
    }

    /**
     *
     */
    public static function resizeMd($img, $enlarge = true)
    {
        $width = $img->width();
        $height = $img->height();

        if ($height > $width) {
            if (!$enlarge && $height <= static::SIZES_MD_HEIGHT) {
                return $img;
            }

            return static::resizeImage($img, null, static::SIZES_MD_HEIGHT);
        } else {
            if (!$enlarge && $width <= static::SIZES_MD_WIDTH) {
                return $img;
            }

            return static::resizeImage($img, static::SIZES_MD_WIDTH, null);
        }
    }

    /**
     *
     */
    public static function resizeLg($img, $enlarge = true)
    {
        $width = $img->width();
        $height = $img->height();

        if ($height > $width) {
            if (!$enlarge && $height <= static::SIZES_LG_HEIGHT) {
                return $img;
            }

            return static::resizeImage($img, null, static::SIZES_LG_HEIGHT);
        } else {
            if (!$enlarge && $width <= static::SIZES_LG_WIDTH) {
                return $img;
            }

            return static::resizeImage($img, static::SIZES_LG_WIDTH, null);
        }
    }

    /**
     *
     */
    public static function resizeXl($img, $enlarge = true)
    {
        $width = $img->width();
        $height = $img->height();

        if ($height > $width) {
            if (!$enlarge && $height <= static::SIZES_XL_HEIGHT) {
                return $img;
            }

            return static::resizeImage($img, null, static::SIZES_XL_HEIGHT);
        } else {
            if (!$enlarge && $width <= static::SIZES_XL_WIDTH) {
                return $img;
            }

            return static::resizeImage($img, static::SIZES_XL_WIDTH, null);
        }
    }

    /**
     *
     */
    public static function resizeImage($img, $width, $height)
    {
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img;
    }
}
