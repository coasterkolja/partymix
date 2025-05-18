<?php

declare(strict_types=1);

namespace App\Support;

class Arr extends \Illuminate\Support\Arr
{
    public static function toObject(array $array): object
    {
        return json_decode(json_encode($array));
    }
}
