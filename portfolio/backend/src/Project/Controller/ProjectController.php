<?php

namespace App\Project\Controller;

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
use App\Project\Service\ProjectRequestHandler;

#[Route('/api/projects')]
class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectFactory $projectFactory,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ProjectRequestHandler $projectRequestHandler
    ) {}

    /**
     * Создание проекта
     */
    #[Route('', name: 'api_project_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $result = $this->projectRequestHandler->handleCreateRequest($request);

        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Категория успешно создана',
                'category' => $this->serializeProject($result->getProject())
            ], Response::HTTP_CREATED);
        }

        return $this->handleErrorResult($result);
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
        $result = $this->projectRequestHandler->handleUpdateRequest($id, $request);

        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Категория успешно обновлена',
                'category' => $this->serializeProject($result->getProject())
            ], Response::HTTP_CREATED);
        }

        return $this->handleErrorResult($result);
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

    /**
     * Обработка ошибок из сервиса
     */
    private function handleErrorResult($result): JsonResponse
    {
        $errorData = [
            'success' => false,
            'error' => $result->getError()
        ];

        if ($result->getErrorDetails()) {
            $errorData['details'] = $result->getErrorDetails();
        }

        $statusCode = match($result->getError()) {
            'Category not found' => Response::HTTP_NOT_FOUND,
            'Категория с таким названием уже существует' => Response::HTTP_CONFLICT,
            'Validation failed' => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_BAD_REQUEST
        };

        return $this->json($errorData, $statusCode);
    }
}