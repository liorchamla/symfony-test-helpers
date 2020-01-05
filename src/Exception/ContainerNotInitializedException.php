<?php


namespace Liior\SymfonyTestHelpers\Exception;

use RuntimeException;

class ContainerNotInitializedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(\sprintf(
            'Container is not initialized. Did you forget to call "%s" or "%s" first?',
            'static::bootKernel()',
            'static::createClient()'
        ));
    }
}
