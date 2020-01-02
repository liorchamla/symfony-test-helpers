<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property ContainerInterface $container
 */
trait WithDatabaseTrait
{

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * Get the Doctrine Manager
     *
     * @return EntityManagerInterface
     */
    protected function getManager(): EntityManagerInterface
    {
        if (!$this->manager) {
            $this->manager = static::$container->get('doctrine.orm.entity_manager');
        }

        return $this->manager;
    }

    /**
     * Get the Doctrine ManagerRegistry
     *
     * @return ManagerRegistry
     */
    protected function getManagerRegistry(): ManagerRegistry
    {
        if (!$this->managerRegistry) {
            $this->managerRegistry = static::$container->get('doctrine');
        }

        return $this->managerRegistry;
    }

    /**
     * Creates many entities and store them in database
     *
     * @param string $entityClassName the fully qualified entity class name
     * @param integer $number the number of instances you want to create
     * @param callable $fn Callable which will receive $entity instance and $index
     *
     * @return array entities that were created
     */
    protected function createMany(string $entityClassName, int $number, callable $fn): array
    {
        $entities = [];

        for ($i = 0; $i < $number; $i++) {
            $entity = new $entityClassName();

            $fn($entity, $i);

            $this->getManager()->persist($entity);

            $entities[] = $entity;
        }

        $this->getManager()->flush();

        return $entities;
    }

    /**
     * Creates one entity in the database
     *
     * @param string $entityClassName Entity fully qualified class name
     * @param callable $fn Callback which will receive $entity instance to tweak it
     *
     * @return object The entity object
     */
    protected function createOne(string $entityClassName, callable $fn)
    {
        $entity = new $entityClassName;

        $fn($entity);

        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        return $entity;
    }

    /**
     * Retrieve a service entity Repository
     *
     * @param string $class Entity fully qualified class name
     *
     * @return ServiceEntityRepository
     */
    protected function getRepository(string $class): ServiceEntityRepository
    {
        return $this->getManagerRegistry()->getRepository($class);
    }
}
