<?php

namespace Spatie\CollectionMacros\Macros;

use Illuminate\Support\Collection;

/**
 * Move elements to the start of the collection.
 *
 * @param  callable  $callable
 *
 * @mixin \Illuminate\Support\Collection
 *
 * @return \Illuminate\Support\Collection|\Illuminate\Support\LazyCollection
 */
class Prioritize
{
    public function __invoke()
    {
        return function (callable $callable): self {
            $nonPrioritized = $this->reject($callable);

            return $this
                ->filter($callable)
                ->union($nonPrioritized);
        };
    }
}
