<?php

namespace Seventyninepr\Wordpress\Boilerplate\Helpers;

class Pluraliser
{
    /**
     * Rough and ready pluraliser :|
     *
     * @param $word
     * @return mixed|string
     */
    public static function pluralise($word)
    {
        if (substr($word, -1) === 'y') {
            return substr_replace($word, 'ies', -1);
        }

        if (in_array(substr($word, -2), ['ch', 'sh', 'ss', 'es'])) {
            return substr_replace($word, 'es', -2);
        }

        if (substr($word, -1) === 'x') {
            return substr_replace($word, 'es', -1);
        }

        if (substr($word, -1) === 'f') {
            return substr_replace($word, 'ves', -1);
        }

        if (substr($word, -1) === 's') {
            return $word;
        }

        return $word .= 's';
    }
}
