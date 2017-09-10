<?php

declare(strict_types=1);

namespace MoustacheBundle\Form\DataTransformer;

use Exception;
use Rico\Lib\ValidationUtils;
use SplFileObject;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class UrlToUploadedFileTransformer implements DataTransformerInterface
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
        if (!$this->validationUtils->isURL($url)) {
            return;
        }

        // @HEYLISTEN Delegate the file creation and the download to classes that handles exception properly
        $temporaryFile = new SplFileObject(tempnam(sys_get_temp_dir(), 'moustache_torrent'), 'a', true);
        $temporaryFile->fwrite(file_get_contents($url, false, null, 0, 100000));

        return new File($temporaryFile->getRealPath(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($file)
    {
    }
}
