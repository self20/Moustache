<?php

namespace Spec\MoustacheBundle\Form\DataTransformer;

use MoustacheBundle\Form\DataTransformer\UrlToMagnetLinkTransformer;
use PhpSpec\ObjectBehavior;
use Rico\Lib\ValidationUtils;
use Symfony\Component\Form\DataTransformerInterface;

class UrlToMagnetLinkTransformerSpec extends ObjectBehavior
{
    public function let(ValidationUtils $validationUtils)
    {
        $validationUtils->isURLMagnet('magnet:url')->willReturn(true);

        $this->beConstructedWith($validationUtils);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UrlToMagnetLinkTransformer::class);
    }

    public function it_is_a_data_transformer()
    {
        $this->shouldHaveType(DataTransformerInterface::class);
    }

    public function it_reverses_transform_to_empty_string_if_value_is_not_a_magnet($validationUtils)
    {
        $validationUtils->isURLMagnet('magnet:url')->willReturn(false);

        $this->reverseTransform('magnet:url')->shouldReturn('');
    }

    public function it_reverses_transform_to_value_itself_if_value_is_a_magnet()
    {
        $this->reverseTransform('magnet:url')->shouldReturn('magnet:url');
    }

    public function it_transforms_magnet_link_to_null()
    {
        $this->transform('magnet:url')->shouldReturn(null);
    }
}
