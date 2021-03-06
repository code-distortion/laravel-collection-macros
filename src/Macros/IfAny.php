<?php

namespace Spatie\CollectionMacros\Macros;

use Illuminate\Support\Collection;

/**
 * Execute a callable if the collection isn't empty, then return the collection.
 *
 * @param callable callback
 *
 * @mixin \Illuminate\Support\Collection
 *
 * @return \Illuminate\Support\Collection|\Illuminate\Support\LazyCollection
 */
class IfAny
{
    public function __invoke()
    {
        return function (callable $callback): self {
            if (! $this->isEmpty()) {
                $callback($this);
            }

            return $this;
        };
    }
}
