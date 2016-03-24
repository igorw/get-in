<?php

namespace igorw;

function get_in(array $array, array $keys, $default = null)
{
    if (!$keys) {
        return $array;
    }

    // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
    if (count($keys) === 1 && isset($array[$keys[0]])) {
        return $array[$keys[0]];
    }

    $current = $array;
    foreach ($keys as $key) {
        if (!is_array($current) || !array_key_exists($key, $current)) {
            return $default;
        }

        $current = $current[$key];
    }

    return $current;
}

function update_in(array $array, array $keys, $f /* , $args... */)
{
    if (!is_callable($f)) {
        throw new \InvalidArgumentException(sprintf(
            'Expected callable. Actual [type=%s]%s.',
            gettype($f),
            is_object($f) ? '[class=' . get_class($f) . ']' : ''
        ));
    }
    
    $args = array_slice(func_get_args(), 3);

    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {
        if (!is_array($current) || !array_key_exists($key, $current)) {
            throw new \InvalidArgumentException(sprintf('Did not find path %s in structure %s', json_encode($keys), json_encode($array)));
        }

        $current = &$current[$key];
    }

    $current = call_user_func_array($f, array_merge(array($current), $args));

    return $array;
}

function assoc_in(array $array, array $keys, $value)
{
    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {

        if (!is_array($current)) {
            $current = array();
        }

        $current = &$current[$key];
    }

    $current = $value;

    return $array;
}
