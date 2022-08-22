<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
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
}
