<?php

namespace Moran\Model\Hydrator;

use \ReflectionClass;
use Moran\Model\Hydrator\Strategy\StrategyInterface;

class ClassMethodsHydrator implements HydratorInterface
{
    protected ?ReflectionClass $reflector = null;

    protected array $strategies = [];

    public function hydrate(array $data, object $model): object
    {
        if ($this->reflector === null) {
            $this->reflector = new ReflectionClass($model);
        }

        foreach ($data as $key => $value) {
            if ($this->hasStrategy($key)) {
                $strategy = $this->strategies[$key];
                $value = $strategy->hydrate($value);
            }

            $methodName = 'set';
            $fieldWords = explode('_', $key);
            foreach($fieldWords as $word) {
                $methodName .= ucfirst($word);
            }

            if ($this->reflector->hasMethod($methodName)) {
                $model->{$methodName}($value);
            }
        }

        return $model;
    }

    public function extract(object $model): array
    {
        return get_object_vars($model);
    }

    public function addStrategy(string $name, StrategyInterface $strategy): void
    {
        $this->strategies[$name] = $strategy; 
    }
    
    public function hasStrategy(string $name): bool
    {
        return array_key_exists($name, $this->strategies);
    }
}
