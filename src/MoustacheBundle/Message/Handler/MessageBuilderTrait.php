<?php

declare(strict_types=1);

namespace MoustacheBundle\Message\Handler;

use Symfony\Component\Translation\TranslatorInterface;

trait MessageBuilderTrait
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    private function buildMessage(string $rawMessage, string ...$parameters)
    {
        return sprintf($rawMessage, ...$parameters);
    }

    private function buildTranslatedMessage(string $rawMessage, string ...$parameters)
    {
        return $this->buildMessage($this->translator->trans($rawMessage), ...$parameters);
    }
}
