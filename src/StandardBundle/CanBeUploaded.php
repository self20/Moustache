<?php

declare(strict_types=1);

namespace StandardBundle;

interface CanBeUploaded
{
    /**
     * @return string
     */
    public function getUploadedFilePathOrMagnet(): string;

    // ---

    /**
     * @return \SplFileInfo|null
     */
    public function getUploadedFileByUrl();

    /**
     * @param \SplFileInfo $uploadedFileByUrl
     */
    public function setUploadedFileByUrl($uploadedFileByUrl = null);

    /**
     * @return \SplFileInfo|null
     */
    public function getUploadedFile();

    /**
     * @param \SplFileInfo $uploadedFile
     */
    public function setUploadedFile(\SplFileInfo $uploadedFile = null);

    /**
     * @return string
     */
    public function getMagnetLink(): string;

    /**
     * @param string $magnetLink
     */
    public function setMagnetLink(string $magnetLink);
}
