<?php

declare(strict_types=1);

namespace App\Controller;

use App\Requests\HistoricalDataRequest;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="app_home")
     * @param HistoricalDataRequest $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(HistoricalDataRequest $request): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $errors = $request->validate();

            if ($errors === null) {

                $data = $request->getRequest()->request->all();

                $this->mailer->sendReportMail($data);
            }
        }

        return $this->render('home/index.html.twig', [
            'errors' => $errors
        ]);
    }
}
