<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Liior\SymfonyTestHelpers\Exception\ProfilerNotEnabledException;
use Symfony\Component\HttpKernel\Profiler\Profile;

trait WithValidatorAssertionsTrait
{
    /** 
     * Asserts that not validations error occured 
     */
    public function assertNoValidatorErrors(): self
    {
        return $this->assertValidatorErrorsCount(0);
    }

    /** 
     * Asserts that a specific number of errors occured in the validation process
     */
    public function assertValidatorErrorsCount(int $count): self
    {
        $violations = $this->getViolationsCount($this->getProfile());

        $this->assertEquals($count, $violations, sprintf('Failed asserting that validator had %s errors, %s found !', $count, $violations));

        return $this;
    }

    /** 
     * Asserts that the validator found an error for the specified propertyPath 
     */
    public function assertValidatorHasError(string $propertyPath): self
    {
        $violations = $this->createConstraintsViolationsArray($this->getProfile());

        $this->assertArrayHasKey(
            $propertyPath,
            $violations,
            sprintf('Failed asserting that "%s" has a validation error', $propertyPath)
        );

        return $this;
    }

    /**
     * Asserts that the validator found a set of errors for the specified properties
     */
    public function assertValidatorHasErrors(array $properties): self
    {
        $violations = $this->createConstraintsViolationsArray($this->getProfile());

        $notExistingProperties = [];

        foreach ($properties as $propertyPath) {
            if (!array_key_exists($propertyPath, $violations)) {
                $notExistingProperties[] = $propertyPath;
            }
        }

        $this->assertEquals(
            0,
            count($notExistingProperties),
            sprintf('Failed asserting that validator has these validation errors : %s', join(', ', $notExistingProperties))
        );

        return $this;
    }

    protected function getViolationsCount(Profile $profile): int
    {
        return $profile->getCollector('validator')->getViolationsCount();
    }

    protected function createConstraintsViolationsArray(Profile $profile): array
    {
        $calls = $profile->getCollector('validator')->getCalls();

        $violations = [];

        foreach ($calls as $call) {
            foreach ($call->violations as $violation) {
                $propertyPath = str_replace('data.', '', $violation->propertyPath);
                $violations[$propertyPath] = $violation->message;
            }
        }

        return $violations;
    }

    protected function getProfile(): Profile
    {
        $profile = $this->client->getProfile();

        if (!$profile) {
            throw new ProfilerNotEnabledException();
        }

        return $profile;
    }
}
