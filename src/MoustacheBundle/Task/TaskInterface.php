<?php

declare(strict_types=1);

namespace MoustacheBundle\Task;

interface TaskInterface
{
    /**
     * @return int 0 when everything is ok
     */
    public function run(): int;

    public function setup();

    public function teardown();
}
