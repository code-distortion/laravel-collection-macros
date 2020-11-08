<?php

namespace Spatie\CollectionMacros\Test\CustomMacros;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\CollectionMacros\Test\TestCase;

class RejectValuesTest extends TestCase
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
    public function rejectValuesRemovesElementsWithLooseComparison($collection)
    {
        $c = new $collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->rejectValues([])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $this->assertEquals(['foo'], $c->rejectValues(['bar'])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $this->assertEquals([], $c->rejectValues(['foo', 'bar'])->values()->all());

        $c = new $collection(['foo', 'bar', null, 0]);
        $this->assertEquals(['foo'], $c->rejectValues(['bar', null])->values()->all());

        $c = new $collection(['foo', 'bar', '123']);
        $this->assertEquals(['foo'], $c->rejectValues(['bar', 123])->values()->all());

        $c = new $collection(['foo', 'bar']);
        $toReject = new $collection(['bar']);
        $this->assertEquals(['foo'], $c->rejectValues($toReject)->values()->all());
    }

    /**
     * @dataProvider collectionClassProvider
     */
    public function testRejectValuesRemovesElementsWithStrictComparison($collection)
    {
        $c = new $collection(['foo', 'bar', null, 0]);
        $this->assertEquals(['foo', 0], $c->rejectValues(['bar', null], true)->values()->all());

        $c = new $collection(['foo', 'bar', '123']);
        $this->assertEquals(['foo', '123'], $c->rejectValues(['bar', 123], true)->values()->all());
    }
}
