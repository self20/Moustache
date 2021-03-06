<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\DataFixtures\Data;

use PhpSpec\ObjectBehavior;
use TorrentBundle\DataFixtures\Data\UserData;

class UserDataSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserData::class);
    }

    public function it_loads_fixtures()
    {
        self::createAll();
    }

    public function it_does_not_load_fixtures_twice()
    {
        self::createAll();
        self::createAll();
    }

    public function it_frees_fixtures()
    {
        self::freeAll();
    }
}
