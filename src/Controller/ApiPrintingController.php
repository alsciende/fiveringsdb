<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Printing;
use App\Repository\CardRepository;
use App\Repository\PackRepository;
use App\Repository\PrintingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiPrintingController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly PackRepository $packRepository,
        private readonly PrintingRepository $printingRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/packs/{packId}/cards/{cardId}', name: 'app_put_printing', methods: ['PUT'])]
    public function put(
        string $packId,
        string $cardId,
        Request $request,
    ): JsonResponse {
        $card = $this->cardRepository->find($cardId);
        $pack = $this->packRepository->find($packId);

        $printing = $this->printingRepository->findOneBy(
            [
                'pack' => $pack,
                'card' => $card,
            ]
        );

        if ($printing instanceof Printing) {
            // Update existing card if it exists
            $this->serializer->deserialize(
                $request->getContent(),
                Printing::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $printing,
                ],
            );
            $this->logger?->info(
                'Updated pack',
                [
                    'pack_id' => $packId,
                    'card_id' => $cardId,
                ]
            );
        } else {
            // Handle creation of a new card if it doesn't exist
            $printing = $this->serializer->deserialize(
                $request->getContent(),
                Printing::class,
                'json',
            );
            $printing->setCard($card);
            $printing->setPack($pack);
            $this->entityManager->persist($printing);
            $this->logger?->info(
                'Created new pack',
                [
                    'pack_id' => $packId,
                    'card_id' => $cardId,
                ]
            );
        }

        $this->entityManager->flush();

        return $this->json($printing);
    }

    #[Route('/packs/{packId}/cards/{cardId}', name: 'app_get_printing', methods: ['GET'])]
    public function get(
        string $packId,
        string $cardId,
    ): JsonResponse {
        $card = $this->cardRepository->find($cardId);
        $pack = $this->packRepository->find($packId);

        $printing = $this->printingRepository->findOneBy(
            [
                'pack' => $pack,
                'card' => $card,
            ]
        );

        if ($printing instanceof Printing) {
            return $this->json($printing);
        }

        throw new NotFoundHttpException();
    }
}
