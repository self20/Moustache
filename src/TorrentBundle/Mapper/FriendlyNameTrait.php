<?php

declare(strict_types=1);

namespace TorrentBundle\Mapper;

use Rico\Lib\StringUtils;

trait FriendlyNameTrait
{
    /**
     * @var StringUtils
     */
    private $stringUtils;

    private function getFriendlyName(string $uglyName): string
    {
        $tempName = $this->stringUtils->removeBracketContent($uglyName);
        $friendlyName = $this->stringUtils->underscoreToSpace($tempName);

        return $friendlyName;
    }
}
