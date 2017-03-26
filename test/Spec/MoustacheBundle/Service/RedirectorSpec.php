<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Service;

use MoustacheBundle\Service\FlashMessengerInterface;
use MoustacheBundle\Service\Redirector;
use MoustacheBundle\Service\RedirectorInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectorSpec extends ObjectBehavior
{
    public function let(
        RouterInterface $router,
        FlashMessengerInterface $flashMessenger
    ) {
        $router->generate('route', ['parameters'])->willReturn('correct uri');

        $this->beConstructedWith($router, $flashMessenger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Redirector::class);
    }

    public function it_is_a_redirector()
    {
        $this->shouldImplement(RedirectorInterface::class);
    }

    public function it_returns_a_redirect_response()
    {
        $this->redirect('route', ['parameters'])->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_redirects_on_the_correct_uri()
    {
        $redirectResponse = $this->redirect('route', ['parameters']);

        $redirectResponse->getTargetUrl()->shouldReturn('correct uri');
    }

    public function it_adds_error_message_to_flashbag($flashMessenger)
    {
        $flashMessenger->error('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addErrorMessage('message', 'more', 'param');
    }

    public function it_adds_info_message_to_flashbag($flashMessenger)
    {
        $flashMessenger->info('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addInfoMessage('message', 'more', 'param');
    }

    public function it_adds_success_message_to_flashbag($flashMessenger)
    {
        $flashMessenger->success('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addSuccessMessage('message', 'more', 'param');
    }

    public function it_adds_warn_message_to_flashbag($flashMessenger)
    {
        $flashMessenger->warn('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addWarnMessage('message', 'more', 'param');
    }
}
