<?php

namespace App\Form;

use App\Service\FolderConfigService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GetKeyType extends AbstractType
{
    public function __construct(
        private readonly FolderConfigService $folderConfigService,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $folderNames = $this->folderConfigService->getFolderNames();
        $choices = array_combine($folderNames, $folderNames);

        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Start Date',
                'help' => 'The date the first image should be displayed.',
            ])
            ->add('folder_name', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Folder',
                'help' => 'The image folder to use.',
            ])
            ->add('ignore_weekends', CheckboxType::class, [
                'required' => false,
                'label' => 'Ignore Weekends',
                'help' => 'Skip Saturday and Sunday when calculating which image to show.',
            ])
        ;
    }
}
