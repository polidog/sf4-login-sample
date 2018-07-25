<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods={"GET"}, name="app_top")
 * @Template()
 */
class TopController
{
    public function __invoke()
    {
        return [];
    }
}
