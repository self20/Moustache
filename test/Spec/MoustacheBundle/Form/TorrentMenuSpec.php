<?php

namespace Spec\MoustacheBundle\Form;

use Closure;
use MoustacheBundle\Form\DataTransformer\UrlToMagnetLinkTransformer;
use MoustacheBundle\Form\DataTransformer\UrlToUploadedFileTransformer;
use MoustacheBundle\Form\TorrentMenu;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TorrentMenuSpec extends ObjectBehavior
{
    public function let(
        UrlToUploadedFileTransformer $urlToUploadedFilTransformer,
        UrlToMagnetLinkTransformer $urlToMagnetLinkTransformer,

        FormBuilderInterface $formBuilder,
        FormBuilderInterface $uploadedFileByUrlChildBuilder,
        FormBuilderInterface $magnetLinkChildBuilder,
        OptionsResolver $optionResolver
    ) {
        $formBuilder->add(Argument::type('string'), Argument::type('string'), Argument::type('array'))->willReturn($formBuilder);
        $formBuilder->get('uploadedFileByUrl')->willReturn($uploadedFileByUrlChildBuilder);
        $formBuilder->get('magnetLink')->willReturn($magnetLinkChildBuilder);
        $formBuilder->addEventListener(Argument::type('string'), Argument::type(Closure::class))->willReturn($formBuilder);

        $this->beConstructedWith($urlToUploadedFilTransformer, $urlToMagnetLinkTransformer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentMenu::class);
    }

    public function it_is_an_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    public function it_builds_the_form($formBuilder)
    {
        $this->buildForm($formBuilder, []);
    }

    public function it_adds_data_transformers_to_some_fields(
        $formBuilder, $magnetLinkChildBuilder, $uploadedFileByUrlChildBuilder, $urlToUploadedFilTransformer, $urlToMagnetLinkTransformer
    ) {
        $uploadedFileByUrlChildBuilder->addModelTransformer($urlToUploadedFilTransformer)->shouldBeCalledTimes(1);
        $magnetLinkChildBuilder->addModelTransformer($urlToMagnetLinkTransformer)->shouldBeCalledTimes(1);

        $this->buildForm($formBuilder, []);
    }

    public function it_configures_default_options($optionResolver)
    {
        $optionResolver->setDefaults(Argument::type('array'))->shouldBeCalledTimes(1);

        $this->configureOptions($optionResolver);
    }
}
