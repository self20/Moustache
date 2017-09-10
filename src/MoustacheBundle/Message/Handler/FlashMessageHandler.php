<?php

declare(strict_types=1);

namespace MoustacheBundle\Message\Handler;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessageHandler implements MessageHandlerInterface
{
    use MessageBuilderTrait;

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
    public function error(string $message, string ...$parameters)
    {
        $this->doAddMessage(self::TYPE_ERROR, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $message, string ...$parameters)
    {
        $this->doAddMessage(self::TYPE_INFO, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function success(string $message, string ...$parameters)
    {
        $this->doAddMessage(self::TYPE_SUCCESS, $message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function warn(string $message, string ...$parameters)
    {
        $this->doAddMessage(self::TYPE_WARN, $message, ...$parameters);
    }

    /**
     * @param string $type
     * @param string $message
     */
    private function doAddMessage(string $type, string $message, string ...$parameters)
    {
        $this->session->getFlashBag()->add($type, $this->buildTranslatedMessage($message, ...$parameters));
    }
}
