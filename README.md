# get-in

[![Build Status](https://travis-ci.org/igorw/get-in.png)](https://travis-ci.org/igorw/get-in)

Functions for for hash map (assoc array) traversal.

When dealing with nested associative structures, traversing them can become
quite a pain. Mostly because of the amount of `isset` checking that is
necessary.

For example, to access a nested key `['foo']['bar']['baz']`, you must do
something like this:

```php
$baz = (isset($data['foo']['bar']['baz'])) ? $data['foo']['bar']['baz'] : null;
```

Enough already! `get-in` provides a better way:

```php
$baz = igorw\get_in($data, ['foo', 'bar', 'baz']);
```

## Installation

Through [composer](http://getcomposer.org):

```bash
$ composer require igorw/get-in:~1.0
```

## Usage

### get_in

Retrieve value from a nested structure using a list of keys:

```php
$users = [
    ['name' => 'Igor Wiedler'],
    ['name' => 'Jane Doe'],
    ['name' => 'Acme Inc'],
];

$name = igorw\get_in($users, [1, 'name']);
//= 'Jane Doe'
```

Non existent keys return null:

```php
$data = ['foo' => 'bar'];

$baz = igorw\get_in($data, ['baz']);
//= null
```
You can provide a default value that will be used instead of null:

```php
$data = ['foo' => 'bar'];

$baz = igorw\get_in($data, ['baz'], 'qux');
//= 'qux'
```
### update_in

Apply a function to the value at a particular location in a nested structure:

```php
$data = ['foo' => ['answer' => 42]];
$inc = function ($x) {
    return $x + 1;
};

$new = igorw\update_in($data, ['foo', 'answer'], $inc);
//= ['foo' => ['answer' => 43]]
```

You can variadically provide additional arguments for the function:

```php
$data = ['foo' => 'bar'];
$concat = function (/* $args... */) {
    return implode('', func_get_args());
};

$new = igorw\update_in($data, ['foo'], $concat, ' is the ', 'best');
//= ['foo' => 'bar is the best']
```

### assoc_in

Set a value at a particular location:

```php
$data = ['foo' => 'bar'];

$new = igorw\assoc_in($data, ['foo'], 'baz');
//= ['foo' => 'baz']
```

It will also set the value if it does not exist yet:

```php
$data = [];

$new = igorw\assoc_in($data, ['foo', 'bar'], 'baz');
//= ['foo' => ['bar' => 'baz']]
```

## Inspiration

The naming and implementation is inspired by the `get-in`, `update-in` and
`assoc-in` functions from [clojure](http://clojure.org).
