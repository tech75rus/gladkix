<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private string $dir_image;
    private string $host;

    public function __construct($dir_image, $host)
    {
        $this->dir_image = $dir_image;
        $this->host = $host . '/images/article/';
    }

    #[Route('/', name: 'app_test')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/image-file', name: 'image-file', methods: ["POST"])]
    public function imageFile(Request $request): ?Response
    {
        /** @var UploadedFile $image */
        $image = $request->files->get('image');
        if (!is_dir($this->dir_image)) {
            mkdir($this->dir_image);
        }
        if (!file_exists($this->dir_image . '/' . $image->getClientOriginalName())) {
            $image->move($this->dir_image, $image->getClientOriginalName());
        }
        $data = [
            'success' => 1,
            'file' => [
                'url' => $this->host . $image->getClientOriginalName()
            ]
        ];
        return new JsonResponse($data, 201);
    }

    #[Route('/image-url', name: 'image-url', methods: ["POST"])]
    public function imageUrl(Request $request): ?Response
    {
        return new Response('image url', 201);
    }
}
