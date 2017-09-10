<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Permission;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Permission\NotEnoughDiskSpaceException;
use TorrentBundle\Exception\Permission\PermissionException;

class NotEnoughDiskSpaceExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NotEnoughDiskSpaceException::class);
    }

    public function it_is_a_permission_exception()
    {
        $this->shouldHaveType(PermissionException::class);
    }

    public function it_sets_and_return_needed_space()
    {
        $this->setNeededSpace('2M');

        $this->getNeededSpace()->shouldReturn('2M');
    }

    public function it_sets_and_return_available_space()
    {
        $this->setAvailableSpace('3.4G');

        $this->getAvailableSpace()->shouldReturn('3.4G');
    }
}
