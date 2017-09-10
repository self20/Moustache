<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Service;

use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use MoustacheBundle\Service\Redirector;
use MoustacheBundle\Service\RedirectorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectorSpec extends ObjectBehavior
{
    public function let(
        RouterInterface $router,
        MessageHandlerInterface $messageHandler
    ) {
        $router->generate('route', ['parameters'])->willReturn('correct uri');
        $router->match(Argument::type('string'))->willReturn(true);

        $this->beConstructedWith($router, $messageHandler);
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

    public function it_redirets_to_an_already_built_path()
    {
        $this->redirectToPath('/existing/route/')->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_redirects_on_the_correct_uri()
    {
        $redirectResponse = $this->redirect('route', ['parameters']);

        $redirectResponse->getTargetUrl()->shouldReturn('correct uri');
    }

    public function it_adds_error_message_to_flashbag($messageHandler)
    {
        $messageHandler->error('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addErrorMessage('message', 'more', 'param');
    }

    public function it_adds_info_message_to_flashbag($messageHandler)
    {
        $messageHandler->info('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addInfoMessage('message', 'more', 'param');
    }

    public function it_adds_success_message_to_flashbag($messageHandler)
    {
        $messageHandler->success('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addSuccessMessage('message', 'more', 'param');
    }

    public function it_adds_warn_message_to_flashbag($messageHandler)
    {
        $messageHandler->warn('message', 'more', 'param')->shouldBeCalledTimes(1);

        $this->addWarnMessage('message', 'more', 'param');
    }
}
