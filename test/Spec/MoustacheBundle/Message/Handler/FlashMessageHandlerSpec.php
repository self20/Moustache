<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Message\Handler;

use MoustacheBundle\Message\Handler\FlashMessageHandler;
use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessageHandlerSpec extends ObjectBehavior
{
    public function let(
        Session $session,
        TranslatorInterface $translator,

        FlashBagInterface $flashBag
    ) {
        $session->getFlashBag()->willReturn($flashBag);

        $translator->trans(Argument::type('string'))->will(function ($args) {
            return 'trans '.$args[0];
        });

        $this->beConstructedWith($session, $translator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FlashMessageHandler::class);
    }

    public function it_is_a_message_handler()
    {
        $this->shouldHaveType(MessageHandlerInterface::class);
    }

    public function it_does_not_add_empty_messages($flashBag)
    {
        $flashBag->add(Argument::cetera())->shouldNotBeCalled();

        $this->error('');
        $this->warn('');
        $this->success('');
        $this->info('');
    }

    public function it_adds_an_error_message_to_the_flash_bag($flashBag)
    {
        $flashBag->add(MessageHandlerInterface::TYPE_ERROR, 'trans message')->shouldBeCalledTimes(1);

        $this->error('message');
    }

    public function it_adds_a_warning_message_to_the_flash_bag($flashBag)
    {
        $flashBag->add(MessageHandlerInterface::TYPE_WARN, 'trans warning message')->shouldBeCalledTimes(1);

        $this->warn('warning message');
    }

    public function it_adds_a_success_message_to_the_flash_bag($flashBag)
    {
        $flashBag->add(MessageHandlerInterface::TYPE_SUCCESS, 'trans success')->shouldBeCalledTimes(1);

        $this->success('success');
    }

    public function it_adds_an_info_message_to_the_flash_bag($flashBag)
    {
        $flashBag->add(MessageHandlerInterface::TYPE_INFO, 'trans info')->shouldBeCalledTimes(1);

        $this->info('info');
    }
}
