<?php

namespace App\Services;

use Exception;

class UuidGenerator
{
    /**
     * @return string
     */
    public static function generate(): string
    {
        try {
            $out = bin2hex(random_bytes(18));

            $out[8] = "-";
            $out[13] = "-";
            $out[18] = "-";
            $out[23] = "-";
            $out[14] = "4";
            $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

            return $out;
        } catch (Exception $e) {
            return '';
        }
    }
}
