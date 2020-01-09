<?php

namespace Liior\SymfonyTestHelpers\Exception;

use RuntimeException;

class ProfilerNotEnabledException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(\sprintf(
            'Profiler was not initialized thus you should not call Validator assertions. Did you forget to call "%s" first?',
            '$client->enableProfiler()'
        ));
    }
}
