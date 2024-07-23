<?php
declare(strict_types=1);

namespace App\Utils;

class ArrayHelper
{
    /**
     * Check if array is list
     *
     * @param array $array
     * @return bool
     */
    public static function arrayIsList(array $array): bool
    {
        $expectedKey = 0;
        foreach ($array as $key => $value) {
            if ($key !== $expectedKey) {
                return false;
            }
            $expectedKey++;
        }
        return true;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array The array to extract values from.
     * @param string|null $key The key using dot notation.
     * @param mixed $default The default value if the key does not exist.
     * @return mixed
     */
    public static function get(array $array, ?string $key, $default = null)
    {
        if (null === $key) {
            return $array;
        }

        $keys = explode('.', $key);

        foreach ($keys as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}
