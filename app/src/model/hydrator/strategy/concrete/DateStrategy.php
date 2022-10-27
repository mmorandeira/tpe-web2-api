<?php

namespace Moran\Model\Hydrator\Strategy;

require_once('./model/hydrator/strategy/StrategyInterface.php');

class DateStrategy implements StrategyInterface
{
    public function hydrate($value) 
    {
        $value = date_create($value);
        return $value;
    }
}