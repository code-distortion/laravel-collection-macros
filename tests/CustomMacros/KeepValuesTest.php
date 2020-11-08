<?php

namespace Spatie\CollectionMacros\Test\CustomMacros;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\CollectionMacros\Test\TestCase;

class KeepValuesTest extends TestCase
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
    public function keepValuesKeepsElementsWithLooseComparison($collection)
    {
        $c = new $collection(['foo', 'bar']);
        $this->assertEquals([], $c->keepValues([])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $this->assertEquals(['bar'], $c->keepValues(['bar'])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->keepValues(['foo', 'bar'])->values()->all());

        $c = new $collection(['foo', 'bar', null, 0]);
        $this->assertEquals(['bar', null, 0], $c->keepValues(['bar', null])->values()->all());

        $c = new $collection(['foo', 'bar', '123']);
        $this->assertEquals(['bar', '123'], $c->keepValues(['bar', 123])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $toKeep = new $collection(['bar']);
        $this->assertEquals(['bar'], $c->keepValues($toKeep)->values()->all());
    }

    /**
     * @dataProvider collectionClassProvider
     */
    public function testKeepValuesKeepsElementsWithStrictComparison($collection)
    {
        $c = new $collection(['foo', 'bar', null, 0]);
        $this->assertEquals(['bar', null], $c->keepValues(['bar', null], true)->values()->all());

        $c = new $collection(['foo', 'bar', '123']);
        $this->assertEquals(['bar'], $c->keepValues(['bar', 123], true)->values()->all());
    }
}
