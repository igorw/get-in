<?php

namespace igorw;

function get_in(array $array, array $keys, $default = null)
{
    if (!$keys) {
        return $array;
    }

    $current = $array;
    foreach ($keys as $key) {
        if (!array_key_exists($key, $current)) {
            return $default;
        }

        $current = $current[$key];
    }
    return $current;
}

function update_in(array $array, array $keys, callable $f /* , $args... */)
{
    $args = array_slice(func_get_args(), 3);

    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {
        if (!array_key_exists($key, $current)) {
            throw new \InvalidArgumentException(sprintf('Did not find path %s in structure %s', json_encode($keys), json_encode($array)));
        }

        $current = &$current[$key];
    }

    $current = call_user_func_array($f, array_merge([$current], $args));

    return $array;
}

function assoc_in(array $array, array $keys, $value)
{
    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {
        if (!array_key_exists($key, $current)) {
            $current[$key] = [];
        }

        $current = &$current[$key];
    }

    $current = $value;

    return $array;
}
