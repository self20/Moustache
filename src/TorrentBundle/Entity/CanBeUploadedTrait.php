<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

trait CanBeUploadedTrait
{
    // @HEYLISTEN De duplicates the assertions
    /**
     * @var \SplFileInfo
     *
     * @Assert\File(
     *     mimeTypes = {"application/x-bittorrent"},
     *     mimeTypesMessage = "Your file was not recognize as a .torrent file.",
     *     maxSize = "100k",
     *     maxSizeMessage = "Your .torrent file was rejected because it is too large.",
     *     disallowEmptyMessage = "The uploaded torrent file seems completely empty.",
     *     uploadIniSizeErrorMessage = "Your torrent file was rejected because it is too big (limited by server). {{ limit }}{{ suffix }} max allowed.",
     *     groups={"default", "torrent_menu"}
     * )
     */
    protected $uploadedFileByUrl = null;

    /**
     * @var \SplFileInfo
     *
     * @Assert\File(
     *     mimeTypes = {"application/x-bittorrent"},
     *     mimeTypesMessage = "Your file was not recognize as a .torrent file.",
     *     maxSize = "100k",
     *     maxSizeMessage = "Your .torrent file was rejected because it is too large.",
     *     disallowEmptyMessage = "The uploaded torrent file seems completely empty.",
     *     uploadIniSizeErrorMessage = "Your torrent file was rejected because it is too big (limited by server). {{ limit }}{{ suffix }} max allowed.",
     *     groups={"default", "torrent_menu"}
     * )
     */
    protected $uploadedFile = null;

    /**
     * {@inheritdoc}
     */
    public function getUploadedFileByUrl()
    {
        return $this->uploadedFileByUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setUploadedFileByUrl(\SplFileInfo $uploadedFileByUrl = null)
    {
        $this->uploadedFileByUrl = $uploadedFileByUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile ?? $this->uploadedFileByUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setUploadedFile(\SplFileInfo $uploadedFile = null)
    {
        $this->uploadedFile = $uploadedFile;
    }
}
