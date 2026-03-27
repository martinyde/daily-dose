<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GetKeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Start Date',
                'help' => 'The date the first image should be displayed (Y-m-d).',
            ])
            ->add('folder_name', TextType::class, [
                'label' => 'Folder Name',
                'help' => 'The folder within daily-files/ where images are stored.',
            ])
            ->add('prefix', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'label' => 'File Prefix',
                'help' => 'Text before the number in file names, e.g. "ch_" for ch_0001.jpg.',
            ])
            ->add('filetype', TextType::class, [
                'data' => 'jpg',
                'label' => 'File Type',
                'help' => 'File extension without the dot.',
            ])
            ->add('digits', IntegerType::class, [
                'data' => 4,
                'label' => 'Digits',
                'help' => 'Number of digits in the file numbering, e.g. 4 for 0001.',
            ])
            ->add('ignore_weekends', CheckboxType::class, [
                'required' => false,
                'label' => 'Ignore Weekends',
                'help' => 'Skip Saturday and Sunday when calculating which image to show.',
            ])
            ->add('start_zero', CheckboxType::class, [
                'required' => false,
                'label' => 'Start at Zero',
                'help' => 'Check if the first image is numbered 0 instead of 1.',
            ])
        ;
    }
}
