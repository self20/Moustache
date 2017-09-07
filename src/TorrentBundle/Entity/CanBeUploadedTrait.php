<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use TorrentBundle\Validator\Constraints as TorrentAssert;

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
     * @var string
     *
     * @TorrentAssert\MagnetLink()
     */
    protected $magnetLink = '';

    /**
     * @Assert\NotBlank(
     *     message="Please upload a valid torrent file or type a torrent URL/magnet.",
     *     groups={"default", "torrent_menu"}
     * )
     *
     * {@inheritdoc}
     */
    public function getUploadedFilePathOrMagnet(): string
    {
        if (null !== $this->uploadedFile) {
            return $this->uploadedFile->getRealPath();
        }

        if (null !== $this->uploadedFileByUrl) {
            return $this->uploadedFileByUrl->getRealPath();
        }

        return $this->magnetLink;
    }

    // ---

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
    public function setUploadedFileByUrl($uploadedFileByUrl = null)
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

    /**
     * {@inheritdoc}
     */
    public function getMagnetLink(): string
    {
        return $this->magnetLink;
    }

    /**
     * {@inheritdoc}
     */
    public function setMagnetLink(string $magnetLink)
    {
        $this->magnetLink = $magnetLink;
    }
}
