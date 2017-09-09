<?php

declare(strict_types=1);

namespace TorrentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MagnetLink extends Constraint
{
    public $message = 'The given link must be a valid magnet.';
}
