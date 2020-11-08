<?php

namespace Spatie\CollectionMacros\Test\CustomMacros;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\CollectionMacros\Test\TestCase;

class KeyedKeysTest extends TestCase
{
    /**
     * Provides each collection class, respectively.
     *
     * @return array
     */
    public function collectionClassProvider()
    {
        return [
            [Collection::class],
            [LazyCollection::class],
        ];
    }



    /**
     * @test
     * @dataProvider collectionClassProvider
     */
    public function generatesValuesFromKeys($collection)
    {
        $c = new $collection(['a' => 'A', 'b' => 'B']);
        $this->assertEquals(['a' => 'a', 'b' => 'b'], $c->keyedKeys()->all());

        $c = new $collection(['A', 'B']);
        $this->assertEquals([0 => 0, 1 => 1], $c->keyedKeys()->all());
    }
}
