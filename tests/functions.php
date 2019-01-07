<?php

/**
 * @param string $class
 * @param array  $attributes
 * @param int    $count
 * @return Illuminate\Database\Eloquent\Model
 */
function create(string $class, array $attributes = [], int $count = null)
{
    return factory($class, $count)->create($attributes);
}