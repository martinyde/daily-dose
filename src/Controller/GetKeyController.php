<?php

namespace App\Controller;

use App\Form\GetKeyType;
use App\Service\KeyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetKeyController extends AbstractController
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {
    }

    #[Route('/get-key', name: 'app_get_key', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(GetKeyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $key = $this->keyService->encode(
                $data['start_date']->format('Y-m-d'),
                $data['folder_name'],
                $data['prefix'] ?? '',
                $data['filetype'],
                $data['digits'],
                $data['ignore_weekends'] ?? false,
                $data['start_zero'] ?? false,
            );

            return $this->redirectToRoute('app_get_key', ['key' => $key]);
        }

        $key = $request->query->get('key');

        return $this->render('get_key/index.html.twig', [
            'form' => $form,
            'key' => $key,
        ]);
    }
}
