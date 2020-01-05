<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
     * @param string|array $expected A string or an array containing an expected row data
     * @param string $entityClassName
     * @param callable|null $qbCustomizer A callable which will receive the QueryBuilder to create a custom query, it will receive 2 params : the QueryBuilder instance and the rootAlias used for the query
     *
     * @return void
     */
    protected function assertDatabaseHas($expected, string $entityClassName, callable $qbCustomizer = null): void
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

        if (\is_array($expected)) {
            $this->assertTrue($this->arrayContainsArray($expected, $data), 'Failed to assert that array was found in database');
            return;
        }

        $this->assertStringContainsString($expected, \serialize($data));
    }

    protected function arrayContainsArray(array $expected, array $array): bool
    {
        foreach ($array as $row) {
            $rowCorresponds = true;

            foreach ($expected as $key => $value) {

                if (!array_key_exists($key, $row)) {
                    // Since we have a query result, if the first row hasn't this column, no row will have
                    throw new Exception("Expected array does not match with database results : key `$key` was not found in results");
                }

                if ($row[$key] != $value) {
                    // No matching value for a key means this row is not what we are looking for, skip to next row
                    $rowCorresponds = false;
                    continue 2;
                }
            }

            if ($rowCorresponds) {
                return true;
            }
        }

        return false;
    }
}
