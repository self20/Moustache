<?php

namespace Spec\MoustacheBundle\Form;

use MoustacheBundle\Form\Signup;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupSpec extends ObjectBehavior
{
    public function let(FormBuilderInterface $formBuilder, OptionsResolver $optionResolver)
    {
        $formBuilder->add(Argument::type('string'), Argument::type('string'), Argument::type('array'))->willReturn($formBuilder);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Signup::class);
    }

    public function it_is_an_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    public function it_builds_the_form($formBuilder)
    {
        $this->buildForm($formBuilder, []);
    }

    public function it_configures_default_options($optionResolver)
    {
        $optionResolver->setDefaults(Argument::type('array'))->shouldBeCalledTimes(1);

        $this->configureOptions($optionResolver);
    }
}
