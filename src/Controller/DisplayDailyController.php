<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class DisplayDailyController extends AbstractController
{
    #[Route('/display/daily/{key}', name: 'app_display_daily')]
    public function index(Request $request, $key): Response
    {
      $key = base64_decode($key);
      $keyParts = explode('|', $key);
      $startDate = DateTime::createFromFormat('Y-m-d', $keyParts[0]);
      $folderName = $keyParts[1];
      $prefix = $keyParts[2];
      $fileType = $keyParts[3];
      $digits = $keyParts[4];
      $ignoreWeekends = TRUE;

      $now = new DateTime();

      if ($ignoreWeekends) {
        $weekdays = 0;
        $current = clone $startDate;
        while ($current <= $now) {
            $day = (int) $current->format('N'); // 1=Mon, 7=Sun
            if ($day < 6) {
                $weekdays++;
            }
            $current->modify('+1 day');
        }
        $difference = $weekdays;
      }
      else {
        $difference = $startDate->diff($now);
      }

      $no = sprintf('%0' . $digits . 'd', $difference->days);
      $fileName = $prefix . $no . '.' . $fileType;

      return $this->render('display_daily/index.html.twig', [
          'controller_name' => 'DisplayDailyController',
          'start_date' => $startDate,
          'diff' => $difference->days,
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
