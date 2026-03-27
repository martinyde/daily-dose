<?php

namespace App\Controller;

use App\Service\KeyService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class DisplayDailyController extends AbstractController
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {
    }

    #[Route('/display/daily/{key}', name: 'app_display_daily')]
    public function index(Request $request, $key): Response
    {
      $params = $this->keyService->decode($key);
      $startDate = DateTime::createFromFormat('Y-m-d', $params['startDate']);
      $folderName = $params['folderName'];
      $prefix = $params['prefix'];
      $fileType = $params['fileType'];
      $digits = $params['digits'];
      $ignoreWeekends = $params['ignoreWeekends'];
      $startZero = $params['startZero'];

      $now = new DateTime();

      if ($ignoreWeekends) {
        $weekdays = 0;
        $current = clone $startDate;
        while ($current < $now) {
            $day = (int) $current->format('N'); // 1=Mon, 7=Sun
            if ($day < 6) {
                $weekdays++;
            }
            $current->modify('+1 day');
        }
        $differenceDays = $weekdays;
      }
      else {
        $difference = $startDate->diff($now->modify('+1 day'));
        $differenceDays = $difference->days;
      }

      if ($startZero) {
          $differenceDays = $differenceDays - 1;
      }

      $no = sprintf('%0' . $digits . 'd', $differenceDays);
      $fileName = $prefix . $no . '.' . $fileType;

          return $this->render('display_daily/index.html.twig', [
          'controller_name' => 'DisplayDailyController',
          'start_date' => $startDate,
          'diff' => $differenceDays,
          'fileName' => $fileName,
          'folder' => $folderName,
      ]);
    }

    #[Route('/iframe-test/{width}/{height}/{key}', name: 'app_iframe_test')]
    public function iframeTest(Request $request, $width, $height, $key): Response
    {
        return $this->render('display_daily/iframe-test.html.twig', [
          'controller_name' => 'DisplayDailyController',
          'key' => $key,
          'width' => $width,
          'height' => $height,
      ]);
    }
}
