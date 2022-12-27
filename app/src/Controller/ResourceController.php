<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CompanySymbolsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    /**
     * @Route("/api/get-company-symbols", name="get-company-symbols")
     */
    public function index(Request $request, CompanySymbolsRepository $companySymbolsRepository): Response
    {
        // this is another moment 
        $data = $companySymbolsRepository->findBySymbol($request->query->get('term'));

        return $this->json($data);
    }
}
