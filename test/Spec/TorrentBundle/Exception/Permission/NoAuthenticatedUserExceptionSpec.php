<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Permission;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Permission\NoAuthenticatedUserException;
use TorrentBundle\Exception\Permission\PermissionException;

class NoAuthenticatedUserExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NoAuthenticatedUserException::class);
    }

    public function it_is_a_permission_exception()
    {
        $this->shouldHaveType(PermissionException::class);
    }
}
