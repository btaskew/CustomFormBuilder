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

/**
 * @param string $class
 * @param array  $attributes
 * @param int    $count
 * @return Illuminate\Database\Eloquent\Model
 */
function make(string $class, array $attributes = [], int $count = null)
{
    return factory($class, $count)->make($attributes);
}

/**
 * @param string $class
 * @param array  $attributes
 * @param int    $count
 * @return array
 */
function raw(string $class, array $attributes = [], int $count = null)
{
    return factory($class, $count)->raw($attributes);
}

/**
 * @param \App\Form $form
 * @return string
 */
function formPath(\App\Form $form): string
{
    return 'forms/' . $form->id;
}