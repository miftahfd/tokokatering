<?php

namespace App\Helpers;

class NumberHelper {
    public static function formatPhoneNumber($no_hp) {
        $first_char_no_hp = $no_hp[0];
        $second_char_no_hp = $no_hp[1];

        if($first_char_no_hp != 6 && $second_char_no_hp != 2) {
            if($first_char_no_hp == 0) $no_hp = ltrim($no_hp, '0');
            $no_hp = "62$no_hp";
        }

        return $no_hp;
    }

    public static function formatRupiah($number) {
        return 'Rp. ' . number_format($number, 0, ',', '.');
    }
}