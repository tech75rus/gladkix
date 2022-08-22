<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(AdminRepository $adminRepository): JsonResponse
    {
        return $this->json([
            'ROLE' => $adminRepository->find(1)->getRoles(),
            'Name' => $adminRepository->find(1)->getUsername(),
            'Password' => $adminRepository->find(1)->getPassword(),
        ]);
    }
}
