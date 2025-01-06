<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class DisplayDailyController extends AbstractController
{
    public function __construct(private KernelInterface $appKernel)
    {
    }

    #[Route('/display/daily/{key}', name: 'app_display_daily')]
    public function index(Request $request, $key): Response
    {
      $key = base64_decode($key);
      $keyParts = explode('|', $key);
      $startDate = DateTime::createFromFormat('Y-m-d', $keyParts[1]);
      if ($keyParts[0] !== $this->getParameter('display_key') || !$startDate) {
        throw new AccessDeniedHttpException();
      }

      $now = new DateTime();

      $difference = $startDate->diff($now);
      $no = sprintf("%04d", $difference->days);
      $fileName = 'ch'. $no . '.jpg';

      $projectDir = $this->appKernel->getProjectDir();

      return $this->render('display_daily/index.html.twig', [
          'controller_name' => 'DisplayDailyController',
          'start_date' => $startDate,
          'diff' => $difference->days,
          'fileName' => $fileName,
      ]);
    }
}
