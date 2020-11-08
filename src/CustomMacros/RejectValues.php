<?php

namespace Spatie\CollectionMacros\CustomMacros;

/**
 * Create a collection of all elements that aren't present in the given list of values.
 *
 * @param array|static $values
 * @param bool $strict
 *
 * @mixin \Illuminate\Support\Collection
 *
 * @return static
 */
class RejectValues
{
    public function __invoke()
    {
        return function ($values, bool $strict = false): self {
            $values = $values instanceof static ? $values->toArray() : $values;

            return $this->filter(function ($value) use ($values, $strict) {
                return ! in_array($value, $values, $strict);
            });
        };
    }
}
