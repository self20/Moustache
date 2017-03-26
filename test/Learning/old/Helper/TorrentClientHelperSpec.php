<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use TorrentBundle\Adapter\TorrentClientInterface;
use TorrentBundle\Exception\NoTorrentClientException;
use TorrentBundle\Helper\HelperInterface;
use TorrentBundle\Helper\TorrentClientHelper;

class TorrentClientHelperSpec extends ObjectBehavior
{
    public function let(
        RequestStack $requestStack,

        Request $request,
        AttributeBagInterface $attributeBag,
        TorrentClientInterface $client
    ) {
        $attributeBag->get(TorrentClientHelper::KEY)->willReturn($client);
        $request->attributes = $attributeBag;
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($requestStack);
    }

    public function it_is_a_helper()
    {
        $this->shouldImplement(HelperInterface::class);
    }

    public function it_throws_an_exception_if_no_adapter_has_been_set($attributeBag)
    {
        $attributeBag->get(TorrentClientHelper::KEY)->willReturn(null);

        $this->shouldThrow(NoTorrentClientException::class)->during('get');
    }

    public function it_throws_an_exception_if_setting_a_non_client_object()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('set', [new \StdClass()]);
    }

    public function it_returns_null_when_no_adapter_has_been_set_in_non_strict_mode($attributeBag)
    {
        $attributeBag->get(TorrentClientHelper::KEY)->willReturn(null);

        $this->getWhenAvailable()->shouldReturn(null);
    }

    public function it_returns_an_client($client)
    {
        $this->get()->shouldReturn($client);
    }

    public function it_sets_a_client($attributeBag, $client)
    {
        $attributeBag->set(TorrentClientHelper::KEY, $client)->shouldBeCalledTimes(1);

        $this->set($client);
    }

    public function it_returns_client_existence()
    {
        $this->isEmpty()->shouldReturn(false);
    }

    public function it_returns_client_existence_when_no_client_has_been_set($attributeBag)
    {
        $attributeBag->get(TorrentClientHelper::KEY)->willReturn(null);

        $this->isEmpty()->shouldReturn(true);
    }
}
