<?php

declare(strict_types=1);

namespace App\Form\Account;

use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => '名前',
            ])
            ->add('email', EmailType::class, [
                'label' => 'メールアドレス',
            ])
            ->add('rawPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'パスワード'],
                'second_options' => ['label' => 'パスワード確認'],
                'required' => true,
            ])
            ;

        $builder->addModelTransformer(new CallbackTransformer(function ($data) {
            return $data;
        }, function ($data) {
            return new Account($data['name'], $data['email'], $data['rawPassword']);
        }));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['translation_domain' => false]);
    }
}
