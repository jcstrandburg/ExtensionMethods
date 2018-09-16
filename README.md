# ExtensionMethods

This library supports C# and kotlin style extension methods for any class that uses the `Extensible` trait. This library differs from some other libraries in that it is not possible for extension methods to get access to private properties and methods, allowing library authors to maintain proper encapsulation.

## Installing

`composer require jcstrandburg\extension-methods`

## Usage

```php
class SomeClass
{
  use trait Extensible;
}

SomeClass::extend('bark', function (SomeClass $x) {
  echo 'Bark!';
});

$s = new SomeClass();
$s->bark();
```

## Version History

### 1.0

#### Added
* `Extensible` trait
