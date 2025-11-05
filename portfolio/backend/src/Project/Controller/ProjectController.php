<?php

namespace App\Api\Controller;

use App\Project\Dto\CreateProjectDto;
use App\Project\Dto\UpdateProjectDto;
use App\Project\Entity\Project;
use App\Project\Factory\ProjectFactory;
use App\Project\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects')]
class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectFactory $projectFactory,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание проекта
     */
    #[Route('', name: 'api_project_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            /** @var CreateProjectDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateProjectDto::class,
                'json'
            );

            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                return $this->json(
                    ['errors' => (string)$errors], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            $project = $this->projectFactory->createFromDto($createDto);

            $this->projectRepository->save($project, true);

            return $this->json([
                'message' => 'Проект успешно создан',
                'project' => $this->serializeProject($project)
            ], Response::HTTP_CREATED);

        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()], 
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Внутренняя ошибка сервера'], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Получение проекта по ID
     */
    #[Route('/{id}', name: 'api_project_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);
        
        if (!$project) {
            return $this->json(
                ['error' => 'Проект не найден'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'project' => $this->serializeProject($project)
        ]);
    }

    /**
     * Получение проекта по slug
     */
    #[Route('/slug/{slug}', name: 'api_project_show_by_slug', methods: ['GET'])]
    public function showBySlug(string $slug): JsonResponse
    {
        $project = $this->projectRepository->findOneBy(['slug' => $slug]);
        
        if (!$project) {
            return $this->json(
                ['error' => 'Проект не найден'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'project' => $this->serializeProject($project)
        ]);
    }

    /**
     * Обновление проекта
     */
    #[Route('/{id}', name: 'api_project_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $project = $this->projectRepository->find($id);
            if (!$project) {
                return $this->json(
                    ['error' => 'Проект не найден'], 
                    Response::HTTP_NOT_FOUND
                );
            }

            /** @var UpdateProjectDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateProjectDto::class,
                'json'
            );

            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return $this->json(
                    ['errors' => (string)$errors], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->projectFactory->updateFromDto($project, $updateDto);

            $this->projectRepository->save($project, true);

            return $this->json([
                'message' => 'Проект успешно обновлен',
                'project' => $this->serializeProject($project)
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()], 
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Внутренняя ошибка сервера'], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Удаление проекта
     */
    #[Route('/{id}', name: 'api_project_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $project = $this->projectRepository->find($id);
        
        if (!$project) {
            return $this->json(
                ['error' => 'Проект не найден'], 
                Response::HTTP_NOT_FOUND
            );
        }

        $this->projectRepository->remove($project, true);

        return $this->json([
            'message' => 'Проект успешно удален'
        ]);
    }

    /**
     * Список проектов
     */
    #[Route('', name: 'api_project_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $limit = $request->query->get('limit', 20);
        
        $criteria = [];
        if ($status && in_array($status, ['planning', 'in_progress', 'completed'])) {
            $criteria['status'] = $status;
        }

        $projects = $this->projectRepository->findBy(
            $criteria, 
            ['createdAt' => 'DESC'],
            $limit
        );

        return $this->json([
            'projects' => array_map([$this, 'serializeProjectForList'], $projects)
        ]);
    }

    /**
     * Проекты по статусу
     */
    #[Route('/status/{status}', name: 'api_project_by_status', methods: ['GET'])]
    public function byStatus(string $status): JsonResponse
    {
        if (!in_array($status, ['planning', 'in_progress', 'completed'])) {
            return $this->json(
                ['error' => 'Недопустимый статус проекта'], 
                Response::HTTP_BAD_REQUEST
            );
        }

        $projects = $this->projectRepository->findBy(
            ['status' => $status], 
            ['createdAt' => 'DESC']
        );

        return $this->json([
            'status' => $status,
            'count' => count($projects),
            'projects' => array_map([$this, 'serializeProjectForList'], $projects)
        ]);
    }

    /**
     * Сериализация проекта для детального просмотра
     */
    private function serializeProject(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'title' => $project->getTitle(),
            'slug' => $project->getSlug(),
            'description' => $project->getDescription(),
            'content' => $project->getContent(),
            'coverImage' => $project->getCoverImage(),
            'projectUrl' => $project->getProjectUrl(),
            'githubUrl' => $project->getGithubUrl(),
            'demoUrl' => $project->getDemoUrl(),
            'status' => $project->getStatus(),
            'statusLabel' => $this->getStatusLabel($project->getStatus()),
            'createdAt' => $project->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $project->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'tags' => array_map(function ($tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName()
                ];
            }, $project->getTags()->toArray())
        ];
    }

    /**
     * Сериализация проекта для списка
     */
    private function serializeProjectForList(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'title' => $project->getTitle(),
            'slug' => $project->getSlug(),
            'description' => $project->getDescription(),
            'coverImage' => $project->getCoverImage(),
            'projectUrl' => $project->getProjectUrl(),
            'githubUrl' => $project->getGithubUrl(),
            'status' => $project->getStatus(),
            'statusLabel' => $this->getStatusLabel($project->getStatus()),
            'createdAt' => $project->getCreatedAt()?->format('Y-m-d H:i:s'),
            'tags' => array_map(function ($tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName()
                ];
            }, $project->getTags()->toArray())
        ];
    }

    /**
     * Получить читабельное название статуса
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'planning' => 'Планирование',
            'in_progress' => 'В разработке',
            'completed' => 'Завершен'
        ];

        return $labels[$status] ?? $status;
    }
}