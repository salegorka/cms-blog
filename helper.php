<?php

function array_get($array, $key, $default = null)
{
    $keys = explode('.', $key);
    $item = $array;
    foreach ($keys as $key) {
        $item = $item[$key];
    }
    return $item ?? $default;
}