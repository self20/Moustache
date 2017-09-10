<?php

declare(strict_types=1);

namespace MoustacheBundle\Task;

interface TaskInterface
{
    public function setup();

    public function teardown();

    /**
     * @return int 0 when everything is ok
     */
    public function run(): int;
}
