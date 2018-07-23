<?php
namespace Astra\SharedBundle\Utils;

abstract class Values
{
    static function getString($value, $nullable = false)
    {
        $value = trim($value);
        if ((empty($value)) && ($nullable)) return null;
        return $value;
    }
}