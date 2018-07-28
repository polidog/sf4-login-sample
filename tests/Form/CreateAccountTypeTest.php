<?php

namespace App\Tests\Form;


use App\Form\CreateAccountType;
use Polidog\LoginSample\Account\Entity\Account;
use Symfony\Component\Form\Test\TypeTestCase;

class CreateAccountTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'テストタロウ',
            'email' => 'test@test.com',
            'rawPassword' => [
                'first' => 'test',
                'second' => 'test'
            ],
        ];

        $form = $this->factory->create(CreateAccountType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        /** @var Account $account */
        $account = $form->getData();
        $this->assertInstanceOf(Account::class, $account);

        $this->assertSame($formData['name'], $account->getName());
        $this->assertSame($formData['email'], $account->getEmail());
    }
}