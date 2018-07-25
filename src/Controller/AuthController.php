<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController
{
    /**
     * @Route("/login")
     * @Template()
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return array
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'error' => $error,
            'last_username' => $lastUsername,
        ];
    }

    /**
     * @Route("/logout", methods={"GET"})
     */
    public function logout(): void
    {
    }
}
