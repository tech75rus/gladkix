<?php

namespace App\Controller;

use App\Entity\TechnologyTag;
use App\Repository\TechnologyTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnologyTagController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/technology-tags', name: 'technology-tags',methods: ["GET"])]
    public function getTechnologyTags(TechnologyTagRepository $tagRepository): ?Response
    {
        $tags = $tagRepository->findAll();
        $response =  $this->json($tags, 201, [], [
            'groups' => 'app'
        ]);

        $response->setSharedMaxAge(3600);

        return $response;
    }

    #[Route('/admin/set-technology-tag', name: 'set-technology-tag', methods: ["POST"])]
    public function setTechnologyTag(Request $request, TechnologyTagRepository $tagRepository): ?Response
    {
        if (!$request->request->has('name')) {
            return new Response('Нет данных', 400);
        }
        $newTagName = $request->request->get('name');
        $tagName = new TechnologyTag();
        $tagName->setName($newTagName);
        $this->entityManager->persist($tagName);
        $this->entityManager->flush($tagName);

        return new Response('Добавлен новый тег ' . $tagName->getName(), 201);
    }

    #[Route('/admin/remove-tag/{id}')]
    #[IsGranted('ROLE_ADMIN')]
    public function removeTag(int $id, TechnologyTagRepository $tagRepository): ?Response
    {
        if (!$id) {
            return new Response('Такого тега не существует', 401);
        }

        $this->entityManager->remove($tagRepository->find($id));
        $this->entityManager->flush();
        return new Response('Тег удален');
    }
}