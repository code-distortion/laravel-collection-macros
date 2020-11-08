# A set of useful Laravel collection macros

[![Latest Version on Packagist](https://img.shields.io/packagist/v/code-distortion/laravel-collection-macros.svg?style=flat-square)](https://packagist.org/packages/code-distortion/laravel-collection-macros)
![PHP from Packagist](https://img.shields.io/packagist/php-v/code-distortion/laravel-collection-macros?style=flat-square)
![Laravel](https://img.shields.io/badge/laravel-7%20%26%208-blue?style=flat-square)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/code-distortion/laravel-collection-macros/run-tests?label=tests&style=flat-square)](https://github.com/code-distortion/laravel-collection-macros/actions)
[![Buy us a tree](https://img.shields.io/badge/treeware-%F0%9F%8C%B3-lightgreen?style=flat-square)](https://ecologi.com/treeware?gift-trees)

This package is a fork of [spatie/laravel-collection-macros](https://github.com/spatie/laravel-collection-macros). It contains a subset of the original macros as well as a few extra ones.

This package is intended for PERSONAL USE. Please see the original Spatie package if you would like to submit a PR or request a feature.



## Installation

Install the package via composer:

``` bash
composer require code-distortion/laravel-collection-macros
```

The package will automatically register itself.



## Macros

Macros kept from the original spatie package:

- [`catch`](#catch)
- [`extract`](#extract)
- [`glob`](#glob)
- [`ifAny`](#ifany)
- [`ifEmpty`](#ifempty)
- [`none`](#none)
- [`paginate`](#paginate)
- [`prioritize`](#prioritize)
- [`simplePaginate`](#simplepaginate)
- [`try`](#try)
- [`transpose`](#transpose)
- [`validate`](#validate)

New macros added:

- [`keepValues`](#keepvalues)
- [`keyedKeys`](#keyedkeys)
- [`rejectValues`](#rejectvalues)



### `catch`

See [`Try`](#try)



### `extract`

Extract keys from a collection. This is very similar to `only`, with two key differences:

- `extract` returns an array of values, not an associative array
- If a value doesn't exist, it will fill the value with `null` instead of omitting it

`extract` is useful when using PHP 7.1 short `list()` syntax.

```php
[$name, $role] = collect($user)->extract('name', 'role.name');
```



### `glob`

Returns a collection of a `glob()` result.

```php
Collection::glob('config/*.php');
```



### `ifAny`

Executes the passed callable if the collection isn't empty. The entire collection will be returned.

```php
collect()->ifAny(function(Collection $collection) { // empty collection so this won't get called
   echo 'Hello';
});

collect([1, 2, 3])->ifAny(function(Collection $collection) { // non-empty collection so this will get called
   echo 'Hello';
});
```



### `ifEmpty`

Executes the passed callable if the collection is empty. The entire collection will be returned.

```php
collect()->ifEmpty(function(Collection $collection) { // empty collection so this will called
   echo 'Hello';
});

collect([1, 2, 3])->ifEmpty(function(Collection $collection) { // non-empty collection so this won't get called
   echo 'Hello';
});
```



### `keepValues`

Returns a collection containing only values that were in the given list.

```php
collect(['foo', 'bar'])->keepValues(['foo'])->toArray(); // ['foo']
```

`keepValues` accepts a second parameter to turn strict-comparisons on (default *false*).

```php
collect(['123', 456])->keepValues(['123', '456'], true)->toArray(); // ['123']
```



### `keyedKeys`

Returns a collection where the values are the same as the keys.

```php
collect(['foo' => 1, 'bar' => 2])->keyedKeys()->toArray(); // ['foo' => 'foo', 'bar' => 'bar']
```



### `none`

Checks whether a collection doesn't contain any occurrences of a given item, key-value pair, or passing truth test. The function accepts the same parameters as the `contains` collection method.

```php
collect(['foo'])->none('bar'); // returns true
collect(['foo'])->none('foo'); // returns false

collect([['name' => 'foo']])->none('name', 'bar'); // returns true
collect([['name' => 'foo']])->none('name', 'foo'); // returns false

collect(['name' => 'foo'])->none(function ($key, $value) {
   return $key === 'name' && $value === 'bar';
}); // returns true
```



### `paginate`

Create a `LengthAwarePaginator` instance for the items in the collection.

```php
collect($posts)->paginate(5);
```

This paginates the contents of `$posts` with 5 items per page. `paginate` accepts quite some options, head over to [the Laravel docs](https://laravel.com/docs/5.4/pagination) for an in-depth guide.

```
paginate(int $perPage = 15, string $pageName = 'page', int $page = null, int $total = null, array $options = [])
```



### `prioritize`

Move elements to the start of the collection.

```php
$collection = collect([
    ['id' => 1],
    ['id' => 2],
    ['id' => 3],
]);

$collection
   ->prioritize(function(array $item) {
      return $item['id'] === 2;
   })
   ->pluck('id')
   ->toArray(); // returns [2, 1, 3]
```



### `rejectValues`

Removes the given values from the collection.

```php
collect(['foo', 'bar'])->rejectValues(['foo'])->toArray(); // ['bar']
```

`rejectValues` accepts a second parameter to turn strict-comparisons on (default *false*).

```php
collect(['123', 456])->rejectValues(['123', '456'], true)->toArray(); // [456]
```



### `simplePaginate`

Create a `Paginator` instance for the items in the collection.

```php
collect($posts)->simplePaginate(5);
```

This paginates the contents of `$posts` with 5 items per page. `simplePaginate` accepts quite some options, head over to [the Laravel docs](https://laravel.com/docs/5.4/pagination) for an in-depth guide.

```
simplePaginate(int $perPage = 15, string $pageName = 'page', int $page = null, int $total = null, array $options = [])
```

For a in-depth guide on pagination, check out [the Laravel docs](https://laravel.com/docs/5.4/pagination).



### `try`

If any of the methods between `try` and `catch` throw an exception, then the exception can be handled in `catch`.

```php
collect(['a', 'b', 'c', 1, 2, 3])
    ->try()
    ->map(fn ($letter) => strtoupper($letter))
    ->each(function() {
        throw new Exception('Explosions in the sky');
    })
    ->catch(function (Exception $exception) {
        // handle exception here
    })
    ->map(function() {
        // further operations can be done, if the exception wasn't rethrow in the `catch`
    });
```

While the methods are named `try`/`catch` for familiarity with PHP, the collection itself behaves more like a database transaction. So when an exception is thrown, the original collection (before the try) is returned.

You may gain access to the collection within catch by adding a second parameter to your handler. You may also manipulate the collection within catch by returning a value.

```php
$collection = collect(['a', 'b', 'c', 1, 2, 3])
    ->try()
    ->map(function ($item) {
        throw new Exception();
    })
    ->catch(function (Exception $exception, $collection) {
        return collect(['d', 'e', 'f']);
    })
    ->map(function ($item) {
        return strtoupper($item);
    });

// ['D', 'E', 'F']
```



### `transpose`

The goal of transpose is to rotate a multidimensional array, turning the rows into columns and the columns into rows.

```php
collect([
    ['Jane', 'Bob', 'Mary'],
    ['jane@example.com', 'bob@example.com', 'mary@example.com'],
    ['Doctor', 'Plumber', 'Dentist'],
])->transpose()->toArray();

// [
//     ['Jane', 'jane@example.com', 'Doctor'],
//     ['Bob', 'bob@example.com', 'Plumber'],
//     ['Mary', 'mary@example.com', 'Dentist'],
// ]
```



### `validate`

Returns `true` if the given `$callback` returns true for every item. If `$callback` is a string or an array, regard it as a validation rule.

```php
collect(['foo', 'foo'])->validate(function ($item) {
   return $item === 'foo';
}); // returns true


collect(['sebastian@spatie.be', 'bla'])->validate('email'); // returns false
collect(['sebastian@spatie.be', 'freek@spatie.be'])->validate('email'); // returns true
```



## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.



## Testing

``` bash
$ composer test
```



## Contributing

Contributions are not being sought for this package. Please see the original [spatie/laravel-collection-macros](https://github.com/spatie/laravel-collection-macros) package if you would like to contribute.



## Security

If you discover any security related issues, please email tim@code-distortion.net instead of using the issue tracker.



## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [Tim Chandler](https://github.com/code-distortion) (fork)
- [All Contributors](../../contributors)



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
