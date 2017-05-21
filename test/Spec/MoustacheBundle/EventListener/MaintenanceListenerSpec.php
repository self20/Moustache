<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\MaintenanceListener;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class MaintenanceListenerSpec extends ObjectBehavior
{
    const FILE = __DIR__.'/.lock';

    public function let(GetResponseEvent $event, Request $request, ParameterBag $parameterBag)
    {
        $parameterBag->has('exception')->willReturn(false);
        $request->attributes = $parameterBag;
        $event->getRequest()->willReturn($request);
        $event->stopPropagation()->willReturn(null);

        $this->beConstructedWith(self::FILE);

        touch(self::FILE);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MaintenanceListener::class);
    }

    public function it_does_nothing_if_no_lock_file_exists($event)
    {
        unlink(self::FILE);

        $this->onKernelRequest($event)->shouldReturn(null);

        touch(self::FILE);
    }

    public function it_does_nothing_if_request_contains_an_exception($parameterBag, $event)
    {
        $parameterBag->has('exception')->willReturn(true);

        $this->onKernelRequest($event)->shouldReturn(null);
    }

    public function it_throws_a_503_exception($event)
    {
        $this->shouldThrow(ServiceUnavailableHttpException::class)->during('onKernelRequest', [$event]);
    }

    public function letGo()
    {
        unlink(self::FILE);
    }
}
