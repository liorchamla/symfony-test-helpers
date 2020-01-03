<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Faker\Factory;
use Faker\Generator;

trait WithFakerTrait
{
    /** @var Generator */
    protected $fakerInstance;

    /** @var string */
    protected $fakerLocale = 'fr_FR';

    protected function fake(): Generator
    {
        if (!$this->fakerInstance) {
            $this->initializeFaker();
        }

        return $this->fakerInstance;
    }

    /**
     * @param callable|null $constructor A callable which will return a faker instance (if you want to tweak it, add providers, etc)
     */
    protected function initializeFaker(?callable $constructor = null): void
    {
        if ($constructor) {
            $instance = $constructor();

            if (!$instance instanceof Generator) {
                throw new \RuntimeException(\sprintf(
                    'Faker created by callable must extend "%s", "%s" given.',
                    Generator::class, \is_object($instance) ? \get_class($instance) : \gettype($instance)
                ));
            }

            $this->fakerInstance = $instance;

            return;
        }

        $this->fakerInstance = Factory::create($this->fakerLocale);
    }
}
