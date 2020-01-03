<?php

namespace Liior\SymfonyTestHelpers\Concerns;

use Faker\Factory;
use Faker\Generator;

trait WithFakerTrait
{
    /**
     * @var Generator
     */
    protected $fakerInstance;

    /**
     * Set faker's generator locale (default fr_FR)
     *
     * @var string
     */
    protected $fakerLocale = "fr_FR";

    /**
     * Get faker instance
     *
     * @param string $locale
     *
     * @return Generator
     */
    protected function fake(): Generator
    {
        if (!$this->fakerInstance) {
            $this->initializeFaker();
        }

        return $this->fakerInstance;
    }

    /**
     * Initialize faker's instance
     *
     * @param callable|null $constructor A callable which will return a faker instance (if you want to tweak it, add providers, etc)
     *
     * @return void
     */
    protected function initializeFaker(?callable $constructor = null)
    {
        if ($constructor) {
            $this->fakerInstance = $constructor();
            return;
        }

        $this->fakerInstance = Factory::create($this->fakerLocale);
    }
}
