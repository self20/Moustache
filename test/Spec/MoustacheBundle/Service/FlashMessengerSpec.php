<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Service;

use MoustacheBundle\Service\FlashMessenger;
use MoustacheBundle\Service\FlashMessengerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessengerSpec extends ObjectBehavior
{
    public function let(
        Session $session,
        TranslatorInterface $translator,

        FlashBagInterface $flashBag
    ) {
        $translator->trans(Argument::type('string'))->will(function ($arg) {
            return 'trans-'.$arg[0];
        });

        $flashBag->add(Argument::type('string'), Argument::type('string'))->willReturn(null);
        $session->getFlashBag()->willReturn($flashBag);

        $this->beConstructedWith($session, $translator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FlashMessenger::class);
    }

    public function it_is_a_flash_messenger()
    {
        $this->shouldImplement(FlashMessengerInterface::class);
    }

    public function it_adds_error_message($flashBag)
    {
        $flashBag->add(FlashMessenger::TYPE_ERROR, 'trans-message-error')->shouldBeCalledTimes(1);

        $this->error('message-error');
    }

    public function it_adds_info_message($flashBag)
    {
        $flashBag->add(FlashMessenger::TYPE_INFO, 'trans-message-info')->shouldBeCalledTimes(1);

        $this->info('message-info');
    }

    public function it_adds_success_message($flashBag)
    {
        $flashBag->add(FlashMessenger::TYPE_SUCCESS, 'trans-message-success')->shouldBeCalledTimes(1);

        $this->success('message-success');
    }

    public function it_adds_warn_message($flashBag)
    {
        $flashBag->add(FlashMessenger::TYPE_WARN, 'trans-message-warn')->shouldBeCalledTimes(1);

        $this->warn('message-warn');
    }
}
