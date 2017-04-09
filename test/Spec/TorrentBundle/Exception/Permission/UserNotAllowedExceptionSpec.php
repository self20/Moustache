<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Permission;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Permission\PermissionException;
use TorrentBundle\Exception\Permission\UserNotAllowedException;

class UserNotAllowedExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserNotAllowedException::class);
    }

    public function it_is_a_permission_exception()
    {
        $this->shouldHaveType(PermissionException::class);
    }
}
