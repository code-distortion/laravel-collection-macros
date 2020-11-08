<?php

namespace Spatie\CollectionMacros\CustomMacros;

/**
 * Create a collection of all elements, where the values are the same as the current keys.
 *
 * @mixin \Illuminate\Support\Collection
 *
 * @return static
 */
class KeyedKeys
{
    public function __invoke()
    {
        return function (): self {
            return $this->map(function ($value, $key) {
                return $key;
            });
        };
    }
}
