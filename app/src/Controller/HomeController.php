<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\HistoricalDataRepository;
use App\Requests\HistoricalDataRequest;
use App\Service\FetchHistoricalData;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private Mailer $mailer;
    private FetchHistoricalData $fetchHistoricalData;
    private HistoricalDataRepository $historicalDataRepository;

    /**
     * @param Mailer $mailer
     * @param FetchHistoricalData $fetchHistoricalData
     * @param HistoricalDataRepository $historicalDataRepository
     */
    public function __construct(
        Mailer $mailer,
        FetchHistoricalData $fetchHistoricalData,
        HistoricalDataRepository $historicalDataRepository
    )
    {
        $this->mailer = $mailer;
        $this->fetchHistoricalData = $fetchHistoricalData;
        $this->historicalDataRepository = $historicalDataRepository;
    }

    /**
     * @Route("/", name="app_home")
     * @param HistoricalDataRequest $request
     * @return Response
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(HistoricalDataRequest $request): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $errors = $request->validate();

            if ($errors === null) {

                $data = $request->getRequest()->request->all();

                $historicalData = $this->historicalDataRepository->getDataBySymbol($data['companySymbol']);

                if (empty($historicalData)) {
                    $this->fetchHistoricalData->getData($data['companySymbol']);
                }

                $historicalData = $this->historicalDataRepository->getDataBySymbol($data['companySymbol']);
                dd($historicalData);
                $this->fetchHistoricalData->getData($data['companySymbol']);


                $this->mailer->sendReportMail($data);
            }
        }

        return $this->render('home/index.html.twig', [
            'errors' => $errors
        ]);
    }
}
