<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Validator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rico\Lib\ValidationUtils;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use TorrentBundle\Validator\Constraints\MagnetLink;
use TorrentBundle\Validator\MagnetLinkValidator;

class MagnetLinkValidatorSpec extends ObjectBehavior
{
    public function let(
        ValidationUtils $validationUtils,
        ExecutionContextInterface $executionContext,

        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        MagnetLink $magnetLinkConstraint
    ) {
        $validationUtils->isURLMagnet('magnet:url')->willReturn(true);

        $executionContext->buildViolation(Argument::any())->willReturn($constraintViolationBuilder);

        $magnetLinkConstraint->message = 'constraint message';

        $this->beConstructedWith($validationUtils);
        $this->initialize($executionContext);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MagnetLinkValidator::class);
    }

    public function it_does_nothing_when_value_is_a_magnet_link($magnetLinkConstraint, $executionContext)
    {
        $executionContext->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validate('magnet:url', $magnetLinkConstraint)->shouldReturn(null);
    }

    public function it_adds_a_violation_when_value_is_not_a_magnet_link($magnetLinkConstraint, $validationUtils, $constraintViolationBuilder, $executionContext)
    {
        $validationUtils->isURLMagnet('magnet:url')->willReturn(false);

        $executionContext->buildViolation('constraint message')->shouldBeCalledTimes(1)->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder->addViolation()->shouldBeCalledTimes(1);

        $this->validate('magnet:url', $magnetLinkConstraint)->shouldReturn(null);
    }
}
