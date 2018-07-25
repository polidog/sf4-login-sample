<?php

declare(strict_types=1);

namespace Polidog\LoginSample\Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use Polidog\LoginSample\Account\Service\PasswordEncoderInterface;

/**
 * ユーザーアカウント.
 *
 * @ORM\Entity()
 * @ORM\Table("accounts")
 * @ORM\HasLifecycleCallbacks()
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var string
     */
    private $rawPassword;

    /**
     * Account constructor.
     *
     * @param string $name
     * @param string $email
     * @param string $rawPassword
     */
    public function __construct(string $name, string $email, string $rawPassword)
    {
        $this->name = $name;
        $this->email = $email;
        $this->rawPassword = $rawPassword;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param PasswordEncoderInterface $encoder
     */
    public function encode(PasswordEncoderInterface $encoder): void
    {
        if (null !== $this->rawPassword) {
            $this->password = $encoder->encode($this->rawPassword);
        }
    }
}
