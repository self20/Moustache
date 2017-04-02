<?php

declare(strict_types=1);

namespace MoustacheBundle\Form;

use MoustacheBundle\Form\DataTransformer\UrlToUploadedFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TorrentBundle\Entity\Torrent;

class TorrentMenu extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadedFile', FileType::class, [
                'data_class' => null,
                'required' => false,
            ])
            ->add('uploadedFileByUrl', TextType::class, [
                'data_class' => null,
                'required' => false,
            ])
            ->get('uploadedFileByUrl')->addModelTransformer(new UrlToUploadedFileTransformer())
        ;
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
