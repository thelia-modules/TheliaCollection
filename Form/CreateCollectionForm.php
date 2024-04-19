<?php

namespace TheliaCollection\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;

class CreateCollectionForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add(
                'item_type',
                ChoiceType::class,
                [
                    'choices' => [
                        $this->translator->trans('content') =>  'content',
                        $this->translator->trans('folder') =>  'folder',
                        $this->translator->trans('product') =>  'product',
                        $this->translator->trans('category') =>  'category',
                    ],
                ]
            )
            ->add(
                'code',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'locale',
                HiddenType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
        ;
    }
}
