<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
   * Returns a PrivateImageExample file for display.
   *
   * @param string $path
   *
   * @return BinaryFileResponse
   */
  #[Route('/serve-private-file/image/{filename}', name: 'serve_private_image')]
  public function privateImageServe(string $filename): BinaryFileResponse
  {
    return $this->fileServe($filename);
  }

  /**
   * Returns a private file for display.
   *
   * @param string $path
   * @return BinaryFileResponse
   */
  private function fileServe(string $fileName): BinaryFileResponse
  {
    $absolutePath = $this->kernel->getProjectDir() . '/daily-files/' . $fileName;

    return new BinaryFileResponse($absolutePath);
  }
}