<?php

namespace Softadastra\Application\Http;

class RedirectionHelper
{
    public static function redirect($url)
    {
        header("Location: /?url=$url");
        exit();
    }

    public static function getUrl(string $path, ?int $id = null, ?string $param = '')
    {
        if (isset($id) && $id != 0) {
            return '/?url=' . $path . '/' . $id;
        } else {
            if (isset($param) && $param != '') {
                return '/?url=' . $path . '?' . $param;
            } else {
                return '/?url=' . $path;
            }
        }
    }
}
