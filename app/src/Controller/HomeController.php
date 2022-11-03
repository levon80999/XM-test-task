<?php

declare(strict_types=1);

namespace App\Controller;

use App\Requests\HistoricalDataRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param HistoricalDataRequest $request
     * @return Response
     */
    public function index(HistoricalDataRequest $request): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $errors = $request->validate();

            dd($errors);
        }

        return $this->render('home/index.html.twig', []);
    }
}
