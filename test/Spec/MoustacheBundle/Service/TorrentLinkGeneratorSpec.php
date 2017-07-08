<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Service;

use MoustacheBundle\Service\TorrentLinkGenerator;
use MoustacheBundle\Service\TorrentLinkGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use TorrentBundle\Entity\TorrentInterface;

class TorrentLinkGeneratorSpec extends ObjectBehavior
{
    public function let(
        SessionInterface $session,
        RouterInterface $router,

        TorrentInterface $torrent
    ) {
        $router->generate(Argument::type('string'), Argument::type('array'))->will(function ($args) {
            return $args[0].' + '.implode(' + ', $args[1]);
        });

        $router->generate(Argument::type('string'))->willReturn('/partial/route');

        $torrent->getName()->willReturn('name');
        $torrent->getHash()->willReturn('hash');

        $session->getId()->willReturn(1);

        $this->beConstructedWith($session, $router, '/some/route');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentLinkGenerator::class);
    }

    public function it_is_a_torrent_link_generator()
    {
        $this->shouldImplement(TorrentLinkGeneratorInterface::class);
    }

    public function it_generates_a_web_link($torrent)
    {
        $this->generateWebLink($torrent)->shouldReturn('moustache_torrent_direct_download + 1a4876706e0c54158d3f7b37ebe1b87bd5a9c10b0f7f7c7d851dfb33feeec72f + name');
    }

    public function it_generates_an_absolute_link($torrent)
    {
        $this->generateAbsoluteLink($torrent)->shouldReturn('/some/route/../webmoustache_torrent_direct_download   1a4876706e0c54158d3f7b37ebe1b87bd5a9c10b0f7f7c7d851dfb33feeec72f   name');
    }

    public function it_generates_a_partial_absolute_link()
    {
        $this->generatePartialAbsoluteLink()->shouldReturn('/some/route/../web/partial/route');
    }
}
