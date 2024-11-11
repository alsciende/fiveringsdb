<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Pack;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiPackController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/packs/{id}', name: 'app_put_pack', methods: ['PUT'])]
    public function put(
        EntityManagerInterface $entityManager,
        string $id,
        Request $request,
    ): JsonResponse {
        $packRepository = $entityManager->getRepository(Pack::class);
        $pack = $packRepository->findOneBy([
            'id' => $id,
        ]);

        if ($pack) {
            // Update existing card if it exists
            $this->serializer->deserialize(
                $request->getContent(),
                Pack::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $pack,
                ],
            );
            $this->logger?->info('Updated pack', [
                'id' => $id,
            ]);
        } else {
            // Handle creation of a new card if it doesn't exist
            $pack = $this->serializer->deserialize(
                $request->getContent(),
                Pack::class,
                'json',
            );
            $entityManager->persist($pack);
            $this->logger?->info('Created new pack', [
                'id' => $id,
            ]);
        }

        $entityManager->flush();

        return $this->json($pack);
    }

    #[Route('/packs/{id}', name: 'app_get_pack', methods: ['GET'])]
    public function get(Pack $pack): JsonResponse
    {
        return $this->json($pack);
    }
}
