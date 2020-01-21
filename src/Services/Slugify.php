<?php

namespace App\Services;

class Slugify
{

    public function generate(string $input): string
    {
        $input = str_replace(" ", "-", $input);
        $input = str_replace(["À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô",
            "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î",
            "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ"], ["A", "A", "A", "A", "A", "A", "C", "E",
            "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a",
            "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u",
            "y", "y"], $input);
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        $input = trim($input);
        $input = preg_replace('/--+/', '-', $input);

        return strtolower($input);
    }

}