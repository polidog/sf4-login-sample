<?php

declare(strict_types=1);

namespace App\Controller;

use Polidog\LoginSample\Account\UseCase\GetAccounts;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods={"GET"}, name="app_top")
 * @Template()
 */
class TopController
{
    /**
     * @var GetAccounts
     */
    private $getAccounts;

    /**
     * TopController constructor.
     *
     * @param GetAccounts $getAccounts
     */
    public function __construct(GetAccounts $getAccounts)
    {
        $this->getAccounts = $getAccounts;
    }

    public function __invoke()
    {
        return [
            'accounts' => $this->getAccounts->all(),
        ];
    }
}
