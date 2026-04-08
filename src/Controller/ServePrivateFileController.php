<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ServePrivateFileController
 * @package App\Controller
 */
class ServePrivateFileController extends AbstractController
{
  public function __construct(
    private readonly KernelInterface $kernel,
  ) {}

    /**
     * Returns image file for display.
     *
     * @param string $filename
     * @param string $folder
     * @return BinaryFileResponse
     */
  #[Route('/serve-file/image/{filename}/{folder}', name: 'serve_image')]
  public function imageServe(string $filename, string $folder): BinaryFileResponse
  {
      try {
          $response = $this->fileServe($filename, $folder);
          $response->setPublic();
          $response->setMaxAge(43200);
          $response->setSharedMaxAge(0);
          $response->headers->addCacheControlDirective('must-revalidate');
          return $response;
      }
      catch (\Exception) {
          throw new AccessDeniedHttpException();
      }
  }

    /**
     * Returns a private file for display.
     *
     * @param string $fileName
     * @param string $folder
     * @return BinaryFileResponse
     */
  private function fileServe(string $fileName, string $folder): BinaryFileResponse
  {
    $absolutePath = $this->kernel->getProjectDir() . '/daily-files/'. $folder . '/'. $fileName;

    return new BinaryFileResponse($absolutePath);
  }
}
