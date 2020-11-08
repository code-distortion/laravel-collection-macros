<?php

namespace Spatie\CollectionMacros;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\ServiceProvider;
use Spatie\CollectionMacros\Exceptions\CannotApplyMacroException;

class CollectionMacroServiceProvider extends ServiceProvider
{
    /**
     * Macros that aren't usable by the LazyCollection.
     *
     * @return string[]
     */
    protected array $nonLazyMacros = [
        'extract',
        'paginate',
        'transpose',
    ];

    /**
     * The Spatie macros to use (the rest are skipped).
     *
     * @return string[]
     */
    protected array $spatiePickList = [
        'extract',
        'ifAny',
        'ifEmpty',
        'none',
        'paginate',
        'prioritize',
        'simplePaginate',
        'transpose',
        'try',
        'validate',
    ];

    /**
     * Additional custom macros.
     *
     * @return string[]
     */
    protected array $customMacros = [
        'keepValues' => \Spatie\CollectionMacros\CustomMacros\KeepValues::class,
        'keyedKeys' => \Spatie\CollectionMacros\CustomMacros\KeyedKeys::class,
        'rejectValues' => \Spatie\CollectionMacros\CustomMacros\RejectValues::class,
    ];

    public function register()
    {
        $spatiePickList = array_combine($this->spatiePickList, $this->spatiePickList);

        $macros = Collection::make($this->macros())
            ->intersectByKeys($spatiePickList) // remove unneeded spatie macros
            ->merge($this->customMacros);

        $macros
            ->reject(fn ($class, $macro) => Collection::hasMacro($macro))
            ->each($this->checkForCollectionMethod(Collection::class))
            ->each(fn ($class, $macro) => Collection::macro($macro, app($class)()));

        $macros
            ->reject(fn ($class, $macro) => in_array($macro, $this->nonLazyMacros))
            ->reject(fn ($class, $macro) => LazyCollection::hasMacro($macro))
            ->each($this->checkForCollectionMethod(LazyCollection::class))
            ->each(fn ($class, $macro) => LazyCollection::macro($macro, app($class)()));
    }

    public function macros(): array
    {
        return [
            'after' => \Spatie\CollectionMacros\Macros\After::class,
            'at' => \Spatie\CollectionMacros\Macros\At::class,
            'before' => \Spatie\CollectionMacros\Macros\Before::class,
            'chunkBy' => \Spatie\CollectionMacros\Macros\ChunkBy::class,
            'collectBy' => \Spatie\CollectionMacros\Macros\CollectBy::class,
            'eachCons' => \Spatie\CollectionMacros\Macros\EachCons::class,
            'eighth' => \Spatie\CollectionMacros\Macros\Eighth::class,
            'extract' => \Spatie\CollectionMacros\Macros\Extract::class,
            'fifth' => \Spatie\CollectionMacros\Macros\Fifth::class,
            'filterMap' => \Spatie\CollectionMacros\Macros\FilterMap::class,
            'firstOrFail' => \Spatie\CollectionMacros\Macros\FirstOrFail::class,
            'fourth' => \Spatie\CollectionMacros\Macros\Fourth::class,
            'fromPairs' => \Spatie\CollectionMacros\Macros\FromPairs::class,
            'glob' => \Spatie\CollectionMacros\Macros\Glob::class,
            'groupByModel' => \Spatie\CollectionMacros\Macros\GroupByModel::class,
            'head' => \Spatie\CollectionMacros\Macros\Head::class,
            'ifAny' => \Spatie\CollectionMacros\Macros\IfAny::class,
            'ifEmpty' => \Spatie\CollectionMacros\Macros\IfEmpty::class,
            'ninth' => \Spatie\CollectionMacros\Macros\Ninth::class,
            'none' => \Spatie\CollectionMacros\Macros\None::class,
            'paginate' => \Spatie\CollectionMacros\Macros\Paginate::class,
            'parallelMap' => \Spatie\CollectionMacros\Macros\ParallelMap::class,
            'pluckToArray' => \Spatie\CollectionMacros\Macros\PluckToArray::class,
            'prioritize' => \Spatie\CollectionMacros\Macros\Prioritize::class,
            'rotate' => \Spatie\CollectionMacros\Macros\Rotate::class,
            'second' => \Spatie\CollectionMacros\Macros\Second::class,
            'sectionBy' => \Spatie\CollectionMacros\Macros\SectionBy::class,
            'seventh' => \Spatie\CollectionMacros\Macros\Seventh::class,
            'simplePaginate' => \Spatie\CollectionMacros\Macros\SimplePaginate::class,
            'sixth' => \Spatie\CollectionMacros\Macros\Sixth::class,
            'sliceBefore' => \Spatie\CollectionMacros\Macros\SliceBefore::class,
            'tail' => \Spatie\CollectionMacros\Macros\Tail::class,
            'tenth' => \Spatie\CollectionMacros\Macros\Tenth::class,
            'third' => \Spatie\CollectionMacros\Macros\Third::class,
            'toPairs' => \Spatie\CollectionMacros\Macros\ToPairs::class,
            'transpose' => \Spatie\CollectionMacros\Macros\Transpose::class,
            'try' => \Spatie\CollectionMacros\Macros\TryCatch::class,
            'validate' => \Spatie\CollectionMacros\Macros\Validate::class,
            'withSize' => \Spatie\CollectionMacros\Macros\WithSize::class,
        ];
    }

    /**
     * Return a method that checks if the given method exists in the Collection class.
     *
     * @param string $collectionClass The Collection class to check against.
     * @return \Closure
     */
    private function checkForCollectionMethod(string $collectionClass)
    {
        return function ($class, $macro) use ($collectionClass) {
            if (method_exists($collectionClass, $macro)) {
                throw CannotApplyMacroException::methodAlreadyExists($macro, $collectionClass);
            }
        };
    }
}
