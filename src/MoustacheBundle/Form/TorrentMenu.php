<?php

declare(strict_types=1);

namespace MoustacheBundle\Form;

use MoustacheBundle\Form\DataTransformer\UrlToMagnetLinkTransformer;
use MoustacheBundle\Form\DataTransformer\UrlToUploadedFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TorrentBundle\Entity\Torrent;

class TorrentMenu extends AbstractType
{
    /**
     * @var UrlToUploadedFileTransformer
     */
    private $urlToUploadedFilTransformer;

    /**
     * @var UrlToMagnetLinkTransformer
     */
    private $urlToMagnetLinkTransformer;

    /**
     * @param UrlToUploadedFileTransformer $urlToUploadedFilTransformer
     * @param UrlToMagnetLinkTransformer   $urlToMagnetLinkTransformer
     */
    public function __construct(UrlToUploadedFileTransformer $urlToUploadedFilTransformer, UrlToMagnetLinkTransformer $urlToMagnetLinkTransformer)
    {
        $this->urlToUploadedFilTransformer = $urlToUploadedFilTransformer;
        $this->urlToMagnetLinkTransformer = $urlToMagnetLinkTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uploadedFile', FileType::class, [
            'data_class' => null,
            'required' => false,
        ]);
        $builder->add('uploadedFileByUrl', TextType::class, [
            'data_class' => null,
            'required' => false,
        ]);
        $builder->add('magnetLink', HiddenType::class, [
            'data_class' => null,
            'required' => false,
        ]);

        $builder->get('uploadedFileByUrl')->addModelTransformer($this->urlToUploadedFilTransformer);
        $builder->get('magnetLink')->addModelTransformer($this->urlToMagnetLinkTransformer);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $data['magnetLink'] = $data['uploadedFileByUrl'] ?? '';
            $event->setData($data);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Torrent::class,
            'validation_groups' => ['torrent_menu'],
        ]);
    }
}
