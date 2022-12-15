<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\TechnologyTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;
    private TechnologyTagRepository $tagRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository, TechnologyTagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/articles', methods: ["GET"])]
    public function getArticles(Request $request): ?Response
    {
        $tag = $request->headers->has('tags') ? $request->headers->get('tags') : '0';
        if ($tag === '0') {
            $articles = $this->articleRepository->findAll();
            $response = $this->json($articles, 200, [], [
                'groups' => 'app'
            ]);
            $response->setSharedMaxAge(3600);
            return $response;
        } else {
            $tag = $this->tagRepository->find($tag);
            $articles = $tag->getArticles();
            if (count($articles) === 0) {
                return new Response('Статьи не найдены', 404);
            }
            return $this->json($articles, 200, [], [
                'groups' => 'app'
            ]);
        }
    }

    #[Route('/article/{id}', name: 'app_admin', methods: ["GET"])]
    public function getArticle(int $id): Response
    {
        $article = $this->articleRepository->find($id);
        return $this->json($article, 200, [], [
            'groups' => 'app'
        ]);
    }

    #[Route('/admin/article-create', name: 'add-article', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function articleCreate(Request $request): ?Response
    {
        if ($request->request->all()) {
            $article = new Article();
            if ($request->request->has('header')) $article->setHeader($request->request->get('header'));
            if ($request->request->has('short_article')) $article->setShortArticle($request->request->get('short_article'));
            if ($request->request->has('article')) $article->setArticle($request->request->get('article'));
            if ($request->request->has('tags')) {
                $tags = explode(',', $request->request->get('tags'));
                foreach ($tags as $tagId) {
                    if ($tag = $this->tagRepository->find($tagId)) {
                        $article->addTechnologyTag($tag);
                    }
                }
            }
            $article->setAtUpdate(new \DateTime('now'));
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return new Response('Статья добавлена', 201);
        }

        return new Response('Нет данных bla bla', 404);
    }

    #[Route('/admin/article-update/{id}', name: 'article-update', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function articleUpdate(Request $request, int $id): ?Response
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return new Response('Статья не существует', 404);
        }
        if ($request->request->all()) {
            if ($request->request->has('header')) $article->setHeader($request->request->get('header'));
            if ($request->request->has('short_article')) $article->setShortArticle($request->request->get('short_article'));
            if ($request->request->has('article')) $article->setArticle($request->request->get('article'));
            $allTags = $this->tagRepository->findAll();
            $requestTags = [];
            if ($request->request->has('tags')) {
                $requestTags = explode(',', $request->request->get('tags'));// Получение тегов из запроса
            }
            foreach ($allTags as $item) {
                if (in_array($item->getId(), $requestTags)) {
                    $article->addTechnologyTag($item);
                } else {
                    $article->removeTechnologyTag($item);
                }
            }
            $article->setAtUpdate(new \DateTime('now'));
            $this->entityManager->flush();
            return new Response('Статья обновлена', 201);
        }
        return new Response('Не получены данные', 400);
    }

    #[Route('/admin/article-delete/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function articleDelete(int $id): ?Response
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return new Response('Статья не существует', 404);
        }
        $header = $article->getHeader();
        $this->entityManager->remove($article);
        $this->entityManager->flush();
        return new Response('Удалена статья ' . $header);
    }
}
