<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use FOS\UserBundle\Security\LoginManagerInterface;
use MoustacheBundle\Service\RedirectorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Templating\EngineInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Manager\UserManager;
use TorrentBundle\Repository\UserRepository;

class SignupController
{
    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var FormInterface
     */
    private $signupForm;

    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var RedirectorInterface
     */
    private $redirector;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param EngineInterface       $templateEngine
     * @param UserManager           $userManager
     * @param UserRepository        $userRepository
     * @param FormInterface         $signupForm
     * @param LoginManagerInterface $loginManager
     * @param RedirectorInterface   $redirector
     * @param Request               $request
     */
    public function __construct(EngineInterface $templateEngine, UserManager $userManager, UserRepository $userRepository, FormInterface $signupForm, LoginManagerInterface $loginManager, RedirectorInterface $redirector, Request $request)
    {
        $this->templateEngine = $templateEngine;
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->signupForm = $signupForm;
        $this->loginManager = $loginManager;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * @param string $confirmationToken
     *
     * @throws AccessDeniedHttpException
     *
     * @return Response
     */
    public function formAction(string $confirmationToken): Response
    {
        $user = $this->getUserByConfirmationToken($confirmationToken, 'Sorry, signup is not available for you. This link has expired or is invalid.');

        $values['formSignup'] = $this->signupForm->setData($user)->createView();
        $values['user'] = $user;

        return $this->templateEngine->renderResponse('MoustacheBundle:Signup:form.html.twig', $values);
    }

    /**
     * @param string $confirmationToken
     *
     * @throws AccessDeniedHttpException
     *
     * @return Response
     */
    public function signupAction(string $confirmationToken): Response
    {
        $user = $this->getUserByConfirmationToken($confirmationToken, 'Sorry, signup is not available for you. Token is invalid.');

        $this->signupForm->setData($user);
        $this->signupForm->handleRequest($this->request);
        if ($this->signupForm->isSubmitted() && $this->signupForm->isValid()) {
            $user->setConfirmationToken(null);
            $user->setEnabled(true);

            $this->loginManager->logInUser('main', $user);

            return $this->redirector->redirect('moustache_torrent');
        }

        $this->redirector->addErrorMessage('%s', $this->signupForm->getErrors(true));

        return $this->redirector->redirect('moustache_signup_form', ['confirmationToken' => $confirmationToken]);
    }

    private function getUserByConfirmationToken(string $confirmationToken, string $messageIfEmpty): UserInterface
    {
        $user = $this->userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        if (empty($user)) {
            throw new AccessDeniedHttpException($messageIfEmpty);
        }

        return $user;
    }
}
