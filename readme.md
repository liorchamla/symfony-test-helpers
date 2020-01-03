# Symfony Test Helpers !

Providing smooth and fresh helpers for your functionnal tests !

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

Get all out of the box just by extending `Liior\SymfonyTestHelpers\WebTestCase` 💪

```php
<?php

namespace Tests\MyCoolFeature;

use Liior\SymfonyTestHelpers\WebTestCase;

class MyCoolTest extends WebTestCase {
  public function testItAllWorksFine() {
    $this->get('/');

    $this->assertSee("Hello World");
  }
}
```

## Details

The library contains several _traits_ and a base class called `Liior\SymfonyTestHelpers\WebTestCase`. By extending the WebTestCase class, you use all the traits, but you can also use only one or several traits on a normal WebTestCase class.

**You can use whatever you see below, out of the box, by extending `Liior\SymfonyTestHelpers\WebTestCase` !**

## WithClientTrait : No need to initialize a client anymore

With this trait, you get a KernelBrowser (aka client) out of the box.

```php
use Liior\SymfonyTestHelpers\Concerns\WithClientTrait;

class MyCoolTest extends WebTestCase {
  use WithClientTrait;

  protected function setUp() : void {
    parent::setUp();

    // You have to initialize the client
    $this->initializeClient();

    // You can also tweak how the client will be built
    $this->initializeClient(function() {
      // Create your client as you want it
      return static::createClient(...);
    });
  }

  public function testItRuns() {
    $this->client->request('GET', '/my/route');
  }
}
```

## WithHttpMethodsTrait : HTTP methods made easy

With this trait, you get shortcuts for 5 HTTP methods :

```php
use Liior\SymfonyTestHelpers\Concerns\WithHttpMethodsTrait;

class MyCoolTest extends WebTestCase {
  use WithHttpMethodsTrait; // uses WithClientTrait internally

  public function testItRuns() {
    $this->get('/'); // equivalent to $this->client->request('GET', '/')
    $this->post('/'); // equivalent to $this->client->request('POST', '/')
    $this->put('/'); // equivalent to $this->client->request('PUT', '/')
    $this->patch('/'); // equivalent to $this->client->request('PATCH', '/')
    $this->delete('/'); // equivalent to $this->client->request('DELETE', '/')
  }
}
```

## WithAuthenticationTrait : Act as an authenticate user with chose roles

With this trait, you get shortcuts methods to act as an authenticate user

```php
use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;

class MyCoolTest extends WebTestCase {
  use WithAuthenticationTrait;

  public function testItRuns() {
    $client = static::createClient();

    $this->authenticate($client, "my_firewall_name");
    $client->request('GET', '/protected/route');

    $this->authenticate($client, "my_firewall_name", ["ROLE_ADMIN"]);
    $client->request('GET', '/admin/foo');

    $this->authenticateAsAdmin($client, "my_firewall_name");
    $client->request('GET', '/admin/foo');
  }
}
```

## WithDatabaseTrait : Interaction with database made easy

With this trait, you get shortcuts methods to act as an authenticate user

```php
use Liior\SymfonyTestHelpers\Concerns\WithDatabaseTrait;

class MyCoolTest extends WebTestCase {
  use WithDatabaseTrait;

  public function testItRuns() {
    $client = static::createClient();

    // Retrieve a repository
    $repository = $this->getRepository(\App\Entity\Task::class);
    $tasks = $repository->findAll();

    // Retrieve a manager
    $task = new \App\Entity\Task();
    $task->setTitle("A task");
    $manager = $this->getManager();
    $manager->persist($task);
    $manager->flush();

    // Shortcut function to create a row inside database (returns the persisted entity)
    $task = $this->createOne(\App\Entity\Task::class, function(\App\Entity\Task $t) {
      $t->setTitle("A task")
        ->setDescription("A description")
        ->setDueDate(new \DateTime());
    });

    // Shortcut function to create several rows inside database (returns an array of persisted entities)
    $tasks = $this->createMany(\App\Entity\Task::class, function(\App\Entity\Task $t, $index) {
      $t->setTitle("Task n°$index")
        ->setDescription("Description for task $index")
        ->setDueDate(new \DateTime());
    });

    // Experimental ! An assertion function which looks up data in a table
    $this->assertDatabaseHas("A title", \App\Entity\Task::class);
  }
}
```

## WithFakerTrait : Using faker made easy

With this trait, you gain access to faker

```php
use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;

class MyCoolTest extends WebTestCase {
  use WithFakerTrait;

  // You can choose your locale (default : fr_FR 👍)
  protected $fakerLocale = "en_GB";

  public function testItRuns() {
    // You can use all faker's methods !
    $sentence = $this->fake()->sentence;
    $paragraph = $this->fake()->paragraph;

    // You can also tweak the way Faker's generator is created
    // and set providers as you want
    $this->initializeFaker(function(){
      $faker = \Faker\Factory::create("fr_FR");
      $faker->addProvider(new \Liior\Faker\Prices($faker));

      return $faker;
    });
  }
}
```
