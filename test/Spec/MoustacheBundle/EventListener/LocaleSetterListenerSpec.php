<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\LocaleSetterListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleSetterListenerSpec extends ObjectBehavior
{
    const FILE = __DIR__.'/.lock';

    public function let(GetResponseEvent $event, Request $request, ParameterBag $parameterBag)
    {
        $parameterBag->has('accept-language')->willReturn(true);
        $parameterBag->get('accept-language')->willReturn('fr_FR');
        $request->headers = $parameterBag;
        $event->getRequest()->willReturn($request);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LocaleSetterListener::class);
    }

    public function it_does_nothing_if_request_has_no_accept_language_header($parameterBag, $request, $event)
    {
        $parameterBag->has('accept-language')->willReturn(false);

        $request->setLocale(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn(null);
    }

    public function it_sets_locale_according_to_accept_laguage_header($request, $event)
    {
        $request->setLocale('fr_FR')->shouldBeCalledTimes(1);

        $this->onKernelRequest($event);
    }

    public function it_returns_an_event($event)
    {
        $this->onKernelRequest($event)->shouldReturn($event);
    }
}
