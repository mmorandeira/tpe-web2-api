<?php

namespace Moran\Model\Hydrator;

interface HydratorInterface
{
    public function hydrate(array $data, object $model): object;

    public function extract(object $model): array;
}