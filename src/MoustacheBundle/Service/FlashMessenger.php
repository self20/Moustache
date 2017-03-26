<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Adds messages to the session flashbag.
 */
class FlashMessenger implements FlashMessengerInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function error(string $message, ...$parameters)
    {
        $this->doAddMessage(self::TYPE_ERROR, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $message, ...$parameters)
    {
        $this->doAddMessage(self::TYPE_INFO, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function success(string $message, ...$parameters)
    {
        $this->doAddMessage(self::TYPE_SUCCESS, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function warn(string $message, ...$parameters)
    {
        $this->doAddMessage(self::TYPE_WARN, $message, ...$parameters);
    }

    /**
     * @param string $type
     * @param string $message
     */
    private function doAddMessage(string $type, string $message, ...$parameters)
    {
        $translatedMessage = $this->translator->trans($message);
        $this->session->getFlashBag()->add($type, sprintf($translatedMessage, ...$parameters));
    }
}
