<?php

declare(strict_types=1);

namespace MoustacheBundle\Form\DataTransformer;

use Rico\Lib\ValidationUtils;
use Symfony\Component\Form\DataTransformerInterface;

class UrlToMagnetLinkTransformer implements DataTransformerInterface
{
    /**
     * @var ValidationUtils
     */
    private $validationUtils;

    /**
     * @param ValidationUtils $validationUtils
     */
    public function __construct(ValidationUtils $validationUtils)
    {
        $this->validationUtils = $validationUtils;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($url)
    {
        // It does not actually transform the $url into a magnet, but it sets it empty when the string is not a magnet.
        // A Transformer is just the easiest way to handle this logic even if Transformers were not meant to be used like this.
        if ($this->validationUtils->isURLMagnet($url)) {
            return $url;
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function transform($magnetLink)
    {
        return null;
    }
}
