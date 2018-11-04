# ExtensionMethods

This library supports C# and kotlin style extension methods for any class that uses the `Extensible` trait. This library differs from some other libraries in that it is not possible for extension methods to get access to private properties and methods, allowing library authors to maintain proper encapsulation.

## Installing

`composer require jcstrandburg\extension-methods`

## Usage

```php
class Person
{
  use trait Extensible;

  public function __construct(string $firstname, string $lastname) {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
  }

  public function getFirstname() {
    return $this->firstname;
  }

  public function getLastname() {
    return $this->lastname;
  }

  private $firstname;
  private $lastname;
}

Person::extend('getFullname', function (Person $x) {
  return $x->getFirstname() . ' ' . $x->getLastname();
});

$bob = new Person('Bob', 'Roberts');
$bob->getFullname() == 'Bob Roberts';
```

## Version History

### Unreleased

### 1.1

#### Changed
* Reworked internal implementation to fix several issues with inheritance. It is now possible to register extension methods for classes extending a base class with the `Extensible` trait even if the extending class itself doesn't have the `Extensible` trait.

### 1.0

#### Added
* `Extensible` trait
