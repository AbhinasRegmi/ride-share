<?php

namespace App\Services;

class LoginCodeService
{
    static public function generate(int $length = 6): string
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= random_int(0, 9);
        }
        return $digits;
    }
}
