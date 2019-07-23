<?php

namespace App\Services;

class Formatter
{

    /**
     * Format product attributes
     *
     * @param array $attributes
     * @return array
     */
    public function formatAttributes(array $attributes)
    {
        $array = [];

        foreach ($attributes as $item) {
            $array[$item['id']] = ['value' => $item['value']];
        }

        return $array;
    }
}
