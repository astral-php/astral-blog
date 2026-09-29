<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Slug ASCII minimal — indépendant d'astral-utilities.
 */
final class Slug
{
    public static function make(string $value, string $separator = '-'): string
    {
        $map = [
            'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'ae',
            'ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i',
            'î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o',
            'ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y',
            'ÿ'=>'y','œ'=>'oe','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A',
            'Å'=>'A','Æ'=>'AE','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E',
            'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O',
            'Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U',
            'Ü'=>'U','Ý'=>'Y','Œ'=>'OE',
        ];

        $value = strtr($value, $map);
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', $separator, $value) ?? '';
        $value = trim($value, $separator);

        return $value !== '' ? $value : 'n-a';
    }
}
