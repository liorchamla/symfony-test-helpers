<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

trait WithDatabaseTrait
{
    use WithContainerTrait;

    /** @var ManagerRegistry */
    protected $managerRegistry;

    /** @var EntityManagerInterface */
    protected $manager;

    protected function getManager(): EntityManagerInterface
    {
        if (!$this->manager) {
            $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        }

        return $this->manager;
    }

    protected function getDoctrine(): ManagerRegistry
    {
        if (!$this->managerRegistry) {
            $this->managerRegistry = $this->getContainer()->get('doctrine');
        }

        return $this->managerRegistry;
    }

    /**
     * @return object[] The persisted entities.
     */
    protected function createMany(string $entityClass, int $numberToCreate, callable $constructor = null): array
    {
        $entities = [];

        for ($i = 0; $i < $numberToCreate; $i++) {
            $entity = $this->createOne($entityClass, $constructor, $i, false);

            $entities[] = $entity;
        }

        $this->getManager()->flush();

        return $entities;
    }

    /**
     * @param string|object $entity Entity class or entity instance.
     * @param mixed $metadata Some data to send to the $constructor callback.
     * @param callable $constructor A callable that receives $entity as argument to customize the object after creation.
     *
     * @return object The persisted entity.
     */
    protected function createOne($entity, callable $constructor = null, $metadata = null, bool $andFlush = true): object
    {
        if (\is_string($entity)) {
            $entity = new $entity;
        }

        if ($constructor) {
            $constructor($entity, $metadata);
        }

        $this->getManager()->persist($entity);

        if ($andFlush) {
            $this->getManager()->flush();
        }

        return $entity;
    }

    protected function getRepository(string $class): EntityRepository
    {
        return $this->getDoctrine()->getRepository($class);
    }

    /**
     * Lookup for all database entries for an entity and find a string in all the properties.
     *
     * @param string $expected
     * @param string $entityClassName
     * @param callable|null $qbCustomizer A callable which will receive the QueryBuilder to create a custom query, it will receive 2 params : the QueryBuilder instance and the rootAlias used for the query
     *
     * @return void
     */
    protected function assertDatabaseHas(string $expected, string $entityClassName, ?callable $qbCustomizer = null): void
    {
        $rootAlias = 'stringThatNoOneWillEverUse';

        $queryBuilder = $this->getManager()
            ->createQueryBuilder()
            ->select($rootAlias)
            ->from($entityClassName, $rootAlias);

        if ($qbCustomizer) {
            $qbCustomizer($queryBuilder, $rootAlias);
        }

        $data = $queryBuilder
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $this->assertStringContainsString($expected, \serialize($data));
    }
}
