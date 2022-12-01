<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\TechnologyTagRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;

    public function __construct($dir_image, $host, EntityManagerInterface $entityManager)
    {
        $this->dir_image = $dir_image;
        $this->host = $host . '/images/article/';
        $this->entityManager = $entityManager;
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
            mkdir($this->dir_image, 0777, true);
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

    #[Route('/tag', name: 'tag', methods: ["GET"])]
    function testTechnologyTag(ArticleRepository $articleRepository, TechnologyTagRepository $technologyTagRepository): ?Response
    {
        $article = $articleRepository->find(14);
        $technologyTag = $technologyTagRepository->find(4);
//        dd($article);
//        $article = new Article();
//        $article->setHeader('Test');
//        $article->setArticle('asdasdasd');
//        $article->setShortArticle('asd');
//        $technologyTag = new TechnologyTag();
//        $technologyTag->setName('test');
//        $article->addTechnologyTag($technologyTag);
//        $this->entityManager->persist($article);
//        $this->entityManager->persist($technologyTag);
//        $this->entityManager->flush();
        $array = [];
        $array['article']['id'] = $article->getId();
        $array['article']['header'] = $article->getHeader();
        $array['article']['article'] = $article->getArticle();
        $array['article']['short_article'] = $article->getShortArticle();
        $array['technology_tag']['id'] = $technologyTag->getId();
        $array['technology_tag']['name'] = $technologyTag->getName();
        return $this->json($array);
        $json = json_encode($array);
        return new Response($json);
    }
}
