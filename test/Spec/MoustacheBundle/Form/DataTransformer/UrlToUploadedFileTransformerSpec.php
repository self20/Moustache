<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Form\DataTransformer;

use MoustacheBundle\Form\DataTransformer\UrlToUploadedFileTransformer;
use PhpSpec\ObjectBehavior;
use Rico\Lib\ValidationUtils;
use SplFileInfo;
use Symfony\Component\Form\DataTransformerInterface;

class UrlToUploadedFileTransformerSpec extends ObjectBehavior
{
    public function let(ValidationUtils $validationUtils)
    {
        $validationUtils->isURL('https://url.com')->willReturn(true);

        $this->beConstructedWith($validationUtils);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UrlToUploadedFileTransformer::class);
    }

    public function it_is_a_data_transformer()
    {
        $this->shouldHaveType(DataTransformerInterface::class);
    }

    public function it_reverses_transform_to_null_if_value_is_not_an_url($validationUtils)
    {
        $validationUtils->isURL('https://url.com')->willReturn(false);

        $this->reverseTransform('https://url.com')->shouldReturn(null);
    }

    // @HEYLISTEN Not possible for now, because it does actual stuff - Fix the class so it’s possible to test
//    public function it_reverses_transform_to_value_itself_if_value_is_a_magnet()
//    {
//        $this->reverseTransform('https://url.com')->shouldReturnType(File::clas);
//    }

    public function it_transforms_file_to_null(SplFileInfo $file)
    {
        $this->transform($file)->shouldReturn(null);
    }
}
