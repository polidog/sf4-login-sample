<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CreateAccountType;
use App\Service\RegisterLogin;
use Polidog\LoginSample\Account\UseCase\RegisterAccount;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/signup")
 */
class SignupController
{
    /**
     * @var RegisterAccount
     */
    private $registerAccount;

    /**
     * @var RegisterLogin
     */
    private $registerLogin;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * SignupController constructor.
     *
     * @param RegisterAccount      $registerAccount
     * @param RegisterLogin        $registerLogin
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     */
    public function __construct(RegisterAccount $registerAccount, RegisterLogin $registerLogin, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->registerAccount = $registerAccount;
        $this->registerLogin = $registerLogin;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @Route("/form", methods={"GET"})
     * @Template()
     */
    public function form(): array
    {
        $form = $this->formFactory->create(CreateAccountType::class);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/form", methods={"POST"})
     * @Template("signup/form.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function save(Request $request)
    {
        $form = $this->formFactory->create(CreateAccountType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return [
                'form' => $form->createView(),
            ];
        }

        $account = $form->getData();
        $this->registerAccount->run($account);
        $this->registerLogin->accountExecute($account);

        return new RedirectResponse($this->router->generate('app_top'));
    }

    /**
     * @Route("/confirm")
     */
    public function confirm()
    {
        return [];
    }
}
