<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CompanySymbolsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param CompanySymbolsRepository $companySymbolsRepository
     * @return Response
     */
    public function index(CompanySymbolsRepository $companySymbolsRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
