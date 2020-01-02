<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Faker\Factory;
use Faker\Generator;

trait WithFakerTrait
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * Get faker instance
     *
     * @param string $locale
     *
     * @return Generator
     */
    protected function faker(string $locale = null): Generator
    {
        if (!$this->faker) {
            $this->faker = Factory::create($locale);
        }

        return $this->faker;
    }
}
