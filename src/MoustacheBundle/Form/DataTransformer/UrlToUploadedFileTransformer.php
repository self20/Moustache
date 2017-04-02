<?php

declare(strict_types=1);

namespace MoustacheBundle\Form\DataTransformer;

use Rico\Slib\ValidationUtils;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class UrlToUploadedFileTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($url)
    {
        if (empty($url) || !ValidationUtils::isURL($url)) {
            return;
        }

        $temporaryFile = new \SplFileObject(tempnam(sys_get_temp_dir(), 'moustache_torrent'), 'a', true);
        $temporaryFile->fwrite(file_get_contents($url));

        return new File($temporaryFile->getRealPath(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return null;
    }
}
