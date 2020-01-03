<?php

namespace Liior\SymfonyTestHelpers;

use Liior\SymfonyTestHelpers\Concerns\WithAuthenticationTrait;
use Liior\SymfonyTestHelpers\Concerns\WithClientAutoInitializeTrait;
use Liior\SymfonyTestHelpers\Concerns\WithDatabaseTrait;
use Liior\SymfonyTestHelpers\Concerns\WithFakerTrait;
use Liior\SymfonyTestHelpers\Concerns\WithClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    use WithAuthenticationTrait;
    use WithClientAutoInitializeTrait;
    use WithClientTrait;
    use WithDatabaseTrait;
    use WithFakerTrait;
}
