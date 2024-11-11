<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCardController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/cards/{id}', name: 'app_put_card', methods: ['PUT'])]
    public function put(
        EntityManagerInterface $entityManager,
        string $id,
        Request $request,
    ): JsonResponse {
        $cardRepository = $entityManager->getRepository(Card::class);
        $card = $cardRepository->findOneBy([
            'id' => $id,
        ]);

        if ($card instanceof Card) {
            // Update existing card if it exists
            $this->serializer->deserialize(
                $request->getContent(),
                Card::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $card,
                ],
            );
            $this->logger?->info('Updated card', [
                'id' => $id,
            ]);
        } else {
            // Handle creation of a new card if it doesn't exist
            $card = $this->serializer->deserialize(
                $request->getContent(),
                Card::class,
                'json',
            );
            $entityManager->persist($card);
            $this->logger?->info('Created new card', [
                'id' => $id,
            ]);
        }

        $entityManager->flush();

        return $this->json($card);
    }

    #[Route('/cards/{id}', name: 'app_get_card', methods: ['GET'])]
    public function get(Card $card): JsonResponse
    {
        return $this->json($card);
    }
}
