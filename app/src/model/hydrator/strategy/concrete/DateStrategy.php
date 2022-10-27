<?php

namespace Moran\Model\Hydrator\Strategy;

class DateStrategy implements StrategyInterface
{
    public function hydrate($value) 
    {
        $value = date_create($value);
        return $value;
    }
}