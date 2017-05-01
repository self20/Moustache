<?php

declare(strict_types=1);

namespace StandardBundle;

interface CanBeUploaded
{
    /**
     * @return \SplFileInfo|null
     */
    public function getUploadedFileByUrl();

    /**
     * @param \SplFileInfo $uploadedFileByUrl
     */
    public function setUploadedFileByUrl(\SplFileInfo $uploadedFileByUrl = null);

    /**
     * @return \SplFileInfo|null
     */
    public function getUploadedFile();

    /**
     * @param \SplFileInfo $uploadedFile
     */
    public function setUploadedFile(\SplFileInfo $uploadedFile = null);
}
