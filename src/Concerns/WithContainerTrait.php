<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ContainerNotInitializedException;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait WithContainerTrait
{
    /**
     * @return ContainerInterface|PsrContainerInterface|TestContainer
     */
    public function getContainer()
    {
        if (!static::$container instanceof ContainerInterface) {
            throw new ContainerNotInitializedException();
        }

        return static::$container;
    }
}
