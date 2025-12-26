# Customizing Data Generation

1. [Faker Data](#faker-data)
    1. [Localized Fake Data](#localized-fake-data)
    1. [Random data](#random-data)
    1. [Default Providers](#default-providers)
        1. [Identity](#identity)
        1. [Current](#current)
        1. [Cast](#cast)
1. [Custom Faker Data Providers](#custom-faker-data-providers)


## Faker Data

Alice integrates with the [Faker][1] library. Using `<foo()>` you can call Faker
data providers to generate random data. Check the
[list of Faker providers](https://fakerphp.github.io/formatters).

Let's turn our static bob user into a randomized entry:

```yaml
Nelmio\Entity\User:
    user{1..10}:
        username: '<username()>'
        fullname: '<firstName()> <lastName()>'
        birthDate: '<date_create()>'
        email: '<email()>'
        favoriteNumber: '<numberBetween(1, 200)>'
```

As you see in the last line, you can also pass arguments to those just as if
you were calling a function.


### Random data

The underlying [Faker][1] library is using a [seed for its data generators][2]
which if set (this is the default) will ensure you will get the same data
between two loadings.

If you wish to generate different data on each loading, you can reset the seed
by overriding the `getSeed()` method when using the `NativeLoader` or the
the parameter `nelmio_alice.seed` if you are using Symfony.


### Localized Fake Data

Faker can create localized data for addresses, phone numbers and so on. You can
set the default locale to use by configuring the `locale` value used by Faker
generator. With `NativeLoader`, this can be done by overriding the
`createFakerGenerator()` method. In Symfony, override the
`nelmio_alice.faker.generator` service.

Additionally, you can mix locales by adding a locale prefix to the faker key,
i.e. `<fr_FR:phoneNumber()>` or `<de_DE:firstName()>`.


### Default Providers

<<<<<<< HEAD
Alice includes a default identity provider, `<identity()>`, that
simply returns whatever is passed to it. This allows you among other
things to use a PHP expression while still benefitting from
[variable replacement](fixtures-refactoring.md#variables). This is similar to an `eval()`
call, allowing you to do things like math or similar, e.g.
`<identity(1 + $favoriteNumber)>`.
=======
Alice default Faker provider can be found in [AliceProvider](../src/Faker/Provider/AliceProvider.php).
>>>>>>> master

### Identity

Alice includes a default identity provider, `<identity()>`, that evaluates whatever
is passed to it and returns the evaluated value. As a result, you can use it to do
arithmetic operations such as `<identity(1 * 2)>` or use PHP expressions like
`<identity(new \DateTimeImmutable('2016-09-16'))>`.

The identity function supports still references and variables so you can still do
`<identity($favoriteNumber * @user1->favoriteNumber)>`. The value of current,
usually used with `<current()>`, is accessible via the `$current` variable.

Some syntactic sugar is provided for this as well, and `<($whatever)>` is an alias
for `<identity($whatever)>`.

**Note:** the behaviour of identity will change in 3.0. It will strictly be equivalent to
an eval at the exception of being able to use references (e.g. `<(@user->name . '!')>`
and variables (e.g. `<($name)>`).


### Current

Returns the current value in the context of a collection:

```yaml
stdClass:
    dummy{1..2}:
        currentValue: <current()> # is equivalent to '$current'
```


### Cast

The cast method was added at some point, but you should use PHP internals like `intval` or `boolval` instead:

```yaml
stdClass:
    dummy{1..2}:
        intval: <intval("1")>
        boolval: <boolval(1)>
```


## Custom Faker Data Providers

<<<<<<< HEAD
Sometimes you need more than what Faker and Alice provide you natively, and
there are three ways to solve the problem:


#### Embed PHP code in the yaml file

It is included by the loader so you can add arbitrary PHP as long as it outputs
valid yaml. That said, this is like PHP templates, it quickly ends up very messy
if you do too much logic, so it's best to extract logic out of the templates.
  

#### Public method in the Loader

All the public methods are available as `<method()>` in the Alice fixture files.
For example if you want a custom group name generator and you use the standard
Doctrine Fixtures package in a Symfony2 project, you could do the following:
=======
Sometimes you need more than what Faker and Alice provide you natively. For
that, you can register a custom [Faker Provider](https://github.com/FakerPHP/Faker/tree/main/src/Faker/Provider) class:
>>>>>>> master

```php
<?php

<<<<<<< HEAD
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;

final class LoadFixtureData implements FixtureInterface
{
   const NAMES = [
       'Group A',
       'Group B',
       'Group C',
   ]

   public function load(ObjectManager $manager)
   {
       // Pass $this as an additional faker provider to make the "groupName"
       // method available as a data provider
       Fixtures::load(__DIR__.'/fixtures.yml', $manager, ['providers' => [$this]]);
   }

   public function groupName()
   {
       return \Faker\Provider\Base::randomElement(self::NAMES);
   }
}
```

That way you can now use `name: '<groupName()>'` to generate specific group names.


#### Add a custom [Faker Provider](https://github.com/fzaninotto/Faker/tree/master/src/Faker/Provider) class

```php
<?php

namespace AppBundle\DataFixtures\ORM;
=======
namespace App\Faker\Provider;
>>>>>>> master

use Faker\Provider\Base as BaseProvider;

final class JobProvider extends BaseProvider
{
   /**
    * Sources: {@link http://siliconvalleyjobtitlegenerator.tumblr.com/}
    *
    * @var array List of job titles.
    */
   const TITLE_PROVIDER = [
       'firstname' => [
           'Audience Recognition',
           'Big Data',
           'Bitcoin',
           '...',
           'Video Experience',
           'Wearables',
           'Webinar',
       ],
       'lastname' => [
           'Advocate',
           'Amplifier',
           'Architect',
           '...',
           'Warlock',
           'Watchman',
           'Wizard',
       ],
       'fullname' => [
           'Conductor of Datafication',
           'Crowd-Funder-in-Residence',
           'Quantified-Self-in-Residence',
           '...',
           'Tech-Svengali-in-Residence',
           'Tech-Wizard-in-Residence',
           'Thought-Leader-in-Residence',
       ],
   ];

   /**
    * Sources: {@link http://sos.uhrs.indiana.edu/Job_Code_Title_Abbreviation_List.htm}
    *
    * @var array List of job abbreviations.
    */
   const ABBREVIATION_PROVIDER = [
       'ABATE',
       'ACAD',
       'ACCT',
       '...',
       'WCTR',
       'WSTRN',
       'WKR',
   ];

   /**
    * @return string Random job title.
    */
   public function jobTitle()
   {
       $names = [
           sprintf(
               '%s %s',
               self::randomElement(self::TITLE_PROVIDER['firstname']),
               self::randomElement(self::TITLE_PROVIDER['lastname'])
           ),
           self::randomElement(self::TITLE_PROVIDER['fullname']),
       ];

       return self::randomElement($names);
   }

   /**
    * @return string Random job abbreviation title
    */
   public function jobAbbreviation()
   {
       return self::randomElement(self::ABBREVIATION_PROVIDER);
   }
}
```

Then you can add it to the Faker Generator used by Alice by either overriding
the `NativeLoader::createFakerGenerator()` method.
 
If you are using Symfony, custom Faker providers are registered by adding the
tag `nelmio_alice.faker.provider` to the services. Note that this is automatically
done if your service extends `Faker\Provider\Base` and have `autoconfigure` and `autowire` enabled:

```yaml
# config/services.yaml

<<<<<<< HEAD
=======
services:
    _defaults:
        autowire: true
        autoconfigure: true

    App\Faker\Provider\JobProvider: ~
```

or:


```yaml
# config/services.yaml

services:
    App\Faker\Provider\JobProvider:
        arguments:
            - '@Faker\Generator'
        tags: [ { name: nelmio_alice.faker.provider } ]
```

>>>>>>> master

<br />
<hr />

<<<<<<< HEAD
« [Keep Your Fixtures Dry](fixtures-refactoring.md) • [Event handling with Processors](processors.md) »
=======
« [Keep Your Fixtures Dry](fixtures-refactoring.md) • [Table of Contents](../README.md#table-of-contents) »


[1]: https://github.com/FakerPHP/Faker
[2]: https://fakerphp.github.io/#seeding-the-generator
>>>>>>> master
