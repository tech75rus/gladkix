<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\AdminRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private string $text;
    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->text = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum accusantium tempore, sapiente nihil repellat, reiciendis in. Sed repellat expedita ipsa, accusantium reiciendis deserunt velit perspiciatis placeat praesentium tenetur esse debitis fugit harum voluptas illum facere impedit? Rem quis, quo unde cupiditate perspiciatis, ab accusamus deserunt, iure, sint quos cumque harum perferendis. Adipisci quam quis necessitatibus fugit quia esse earum vel repellat rerum. Delectus, accusantium cumque voluptatibus molestias a quae eveniet nobis necessitatibus vel autem sed amet dolore iste recusandae neque magni, in aspernatur possimus asperiores pariatur ea tempora mollitia. Harum ipsum tempora consequuntur ducimus autem, explicabo ipsam laborum officia dolore, inventore, veritatis. Ea ipsa laboriosam dolore voluptates, mollitia iusto inventore, repudiandae voluptatibus fugit nostrum, soluta! Molestias in provident, placeat temporibus, veritatis vel repellat autem culpa quas illum, aut cumque. Necessitatibus explicabo aperiam modi illo accusamus neque dolores eaque, error deserunt illum tempore molestias voluptates nostrum voluptatibus expedita ipsa! Eligendi nam ratione, voluptatem, sapiente atque harum est inventore a quibusdam quia magnam, consequatur nisi eos ducimus optio, beatae repellendus facere quaerat! Commodi nihil velit harum, ea dolore officiis mollitia sunt id corporis, voluptatibus minima. Nesciunt architecto error tempore, dolore nemo ex laudantium voluptatem eius corporis laboriosam reiciendis quas natus tempora, cum eaque necessitatibus doloremque. Architecto quam rem tempore saepe cupiditate, expedita quod sunt sint soluta temporibus optio ipsum harum corporis magni et blanditiis sit atque recusandae aspernatur consequatur quos cum commodi impedit error. Nam officiis asperiores veniam eaque molestias placeat quisquam, quas error qui quibusdam dolore sit assumenda ea at dolores.';
        $this->articleRepository = $articleRepository;
    }
    #[Route('/admin/data', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(AdminRepository $adminRepository): JsonResponse
    {
        return $this->json([
            'ROLE' => $adminRepository->find(1)->getRoles(),
            'Name' => $adminRepository->find(1)->getUsername(),
            'Password' => $adminRepository->find(1)->getPassword(),
        ]);
    }

    #[Route('/admin/add-article', name: 'add-article', methods: ["POST", "GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function setArticle(Request $request, ArticleRepository $articleRepository): ?Response
    {
        //$articleRepository->find(1);
        //$data = $request->request->get('data');
        $article = new Article();
        $article->setHeader('Первая статья');
        $article->setArticle($this->text);
        $article->setAtCreate(new \DateTime('now'));
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return new Response('added article', 201);
    }

    #[Route('/admin/update-article/{id}')]
    public function updateArticle(int $id): ?Response
    {
        $article = $this->articleRepository->find($id);
        $article->setHeader('Обновленный заголовок');
        $this->entityManager->flush();
        return new Response('Update article', 201);
    }
}
