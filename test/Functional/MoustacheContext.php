<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class MoustacheContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;
    use AuthenticationContextTrait;
    use DatabaseContextTrait;
    use DebugContextTrait;
    use RedirectionContextTrait;
}
