# Symfony Test Helpers

Providing smooth and fresh helpers for your functional tests!

## Contents

1. [Installation](#installation)
1. [Basic Usage](#basic-usage)
1. [Details](#details)

## Installation

```bash
composer require liorchamla/symfony-test-helpers
```

Since _it is not a bundle_, you don't need any more configuration 👍

## Basic Usage

Get everything out of the box by extending `Liior\SymfonyTestHelpers\WebTestCase` 💪

```php
<?php

namespace Tests\MyCoolFeature;

use Liior\SymfonyTestHelpers\WebTestCase;

class MyCoolTest extends WebTestCase
{
    public function testItAllWorksFine(): void
    {
        $this->get('/');

        $this->assertSee('Hello World!');
    }
}
```

## Details

The library contains several _traits_ and a base class called `Liior\SymfonyTestHelpers\WebTestCase`. By extending this `WebTestCase` class, you automatically use all the traits, but you can also use only one or several traits on a normal WebTestCase class.

**You can use whatever you see below, out of the box, by extending `Liior\SymfonyTestHelpers\WebTestCase`**

## WithClientTrait part 1: No need to initialize a client anymore

With this trait, you get a `KernelBrowser` (a.k.a. client) out of the box.

```php
<?php

use Liior\SymfonyTestHelpers\Concerns\WithClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCoolTest extends WebTestCase
{
    use WithClientTrait;

    public function testItRuns(): void
    {
        $this->get('/my/route');

        $this->assertSee('Hello World!');
    }
}
```

## WithClientTrait part 2: HTTP methods made easy

With this trait, you get shortcuts for five HTTP methods :

```php
<?php

use Liior\SymfonyTestHelpers\Concerns\WithClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCoolTest extends WebTestCase
{
    use WithClientTrait;

    public function testItRuns()
    {
        $this->get('/');    // equivalent to $this->client->request('GET', '/')
        $this->post('/');   // equivalent to $this->client->request('POST', '/')
        $this->put('/');    // equivalent to $this->client->request('PUT', '/')
        $this->patch('/');  // equivalent to $this->client->request('PATCH', '/')
        $this->delete('/'); // equivalent to $this->client->request('DELETE', '/')
    }
}
```

## WithAuthenticationTrait: Act as an authenticated user with chosen roles

With this trait, you get shortcut methods to act as an authenticated user:

```php
<?php

use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCoolTest extends WebTestCase
{
    use WithAuthenticationTrait;

    public function testItRuns(): void
    {
        $client = static::createClient();

        // Authenticate with dummy username, don't need the user to exist in the database
        $this->authenticate($client, "fakeUserName", 'my_firewall_name');
        $client->request('GET', '/protected/route');

        // You can pass custom roles for your simulated user
        $this->authenticate($client, "fakeUserName", 'my_firewall_name', ['ROLE_ADMIN']);
        $client->request('GET', '/admin/foo');

        // A shortcut to authenticate as an admin
        $this->authenticateAsAdmin($client, "fakeUserName", 'my_firewall_name');
        $client->request('GET', '/admin/foo');
    }
}
```

You can also authenticate with a real user !

```php
<?php

use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;
use Liior\SymfonyTestHelpers\Concerns\WithDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class MyCoolTest extends WebTestCase
{
    use WithAuthenticationTrait;
    use WithDatabaseTrait;

    public function testItRuns(): void
    {
        $client = static::createClient();

        // Create a user (it must be an instance of UserInterface)
        $user = new User;
        $user->setEmail("any@email.com")
            ->setPassword("password")
            ->setRoles(['ROLE_MANAGER', 'ROLE_AUTHOR']);

        // You get this from WithDatabaseTrait
        $this->getManager()->persist($user);
        $this->getManager()->flush();

        // Then you can authenticate with this user
        $this->authenticate($client, $user, "my_firewall_name");
        $client->request('GET', '/protected/route');

        // You can also override roles :
        $this->authenticate($client, $user, "my_firewall_name", ['ROLE_MODERATOR', 'ROLE_ADMIN']);
        $client->request('GET', '/admin/foo');
    }
}
```

## WithDatabaseTrait: Interaction with database made easy

With this trait, you can retrieve Doctrine EntityManager, Repositories and assert that a string is found in a table (experimental)

```php
<?php

use App\Entity\Task;
use Liior\SymfonyTestHelpers\Concerns\WithDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCoolTest extends WebTestCase
{
    use WithDatabaseTrait;

    public function testItRuns(): void
    {
        $client = static::createClient();

        // Retrieve a repository
        $repository = $this->getRepository(Task::class);
        $tasks = $repository->findAll();

        // Retrieve a manager
        $task = new Task();
        $task->setTitle('A task');
        $manager = $this->getManager();
        $manager->persist($task);
        $manager->flush();

        // Shortcut function to create a row inside database (returns the persisted entity)
        $task = $this->createOne(Task::class, function(Task $entity) {
            $entity
                ->setTitle("A task")
                ->setDescription("A description")
                ->setDueDate(new \DateTime())
            ;
        });

        // Shortcut function to create several rows inside database (returns an array of persisted entities)
        $tasks = $this->createMany(Task::class, function(Task $entity, int $index) {
            $entity
                ->setTitle("Task n°$index")
                ->setDescription("Description for task $index")
                ->setDueDate(new \DateTime('+'.$index.' days'))
            ;
        });

        // Experimental! An assertion function which looks up data in a table
        // With a simple string
        $this->assertDatabaseHas('A title', Task::class);

        // With an array of data
        $this->assertDatabaseHas([
            'title' => 'A title',
            'description' => 'A description'
        ], Task::class);

        // With a customized query
        $this->assertDatabaseHas('James', Task::class, function(\Doctrine\ORM\QueryBuilder $qb) {
            $qb->addSelect('a.name')
                ->innerJoin($root. '.author', 'a');
        });
    }
}
```

## WithFakerTrait: Using faker made easy

With this trait, you gain access to faker.

```php
<?php

use Faker\Factory;
use Liior\Faker\PricesProvider;
use Liior\SymfonyTestHelpers\Concerns\WithFakerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyCoolTest extends WebTestCase
{
    use WithFakerTrait;

    // You can choose your locale (default is fr_FR 👍)
    protected $fakerLocale = 'en_GB';

    public function testItRuns()
    {
        // You can use all faker's methods!
        $sentence = $this->fake()->sentence;
        $paragraph = $this->fake()->paragraph;

        // You can also tweak the way Faker's generator is created
        // and set providers as you want.
        $this->initializeFaker(function(){
            $faker = Factory::create('fr_FR');
            $faker->addProvider(new PricesProvider($faker));

            return $faker;
        });
    }
}
```
