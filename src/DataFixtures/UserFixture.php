<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Polidog\LoginSample\Account\Entity\Account;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixture constructor.
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function __construct(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $account = new Account('test', 'test@test.com','test');
        $account->encode($this->passwordEncoder);

        $manager->persist($manager);
        $manager->flush();

    }

}