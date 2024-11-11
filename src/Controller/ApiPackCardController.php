<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Card;
use App\Entity\PackCard;
use App\Repository\CardRepository;
use App\Repository\PackCardRepository;
use App\Repository\PackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse};
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ApiPackCardController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly CardRepository $cardRepository,
        private readonly PackRepository $packRepository,
        private readonly PackCardRepository $packCardRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/pack_cards/{packId}/{cardId}', name: 'app_put_pack_card', methods: ['PUT'])]
    public function put(
        string $packId,
        string $cardId,
        #[MapRequestPayload] PackCard $packCard,
    ): JsonResponse {
        $card = $this->cardRepository->find($cardId);
        $pack = $this->packRepository->find($packId);

        $existingPackCard = $this->packCardRepository->findOneBy(
            [
                'pack' => $pack,
                'card' => $card,
            ]
        );

        if ($existingPackCard instanceof PackCard) {
            // Update existing card if it exists
            $existingPackCard->updateFrom($packCard);
            $this->logger?->info(
                'Updated pack',
                [
                    'pack_id' => $packId,
                    'card_id' => $cardId,
                ]
            );
        } else {
            // Handle creation of a new card if it doesn't exist
            $packCard->setCard($card);
            $packCard->setPack($pack);
            $this->entityManager->persist($packCard);
            $this->logger?->info(
                'Created new pack',
                [
                    'pack_id' => $packId,
                    'card_id' => $cardId,
                ]
            );
        }

        $this->entityManager->flush();

        return $this->json($packCard);
    }

    #[Route('/pack_cards/{packId}/{cardId}', name: 'app_get_pack_card', methods: ['GET'])]
    public function get(
        string $packId,
        string $cardId,
    ): JsonResponse {
        $card = $this->cardRepository->find($cardId);
        $pack = $this->packRepository->find($packId);

        $packCard = $this->packCardRepository->findOneBy(
            [
                'pack' => $pack,
                'card' => $card,
            ]
        );

        if ($packCard instanceof PackCard) {
            return $this->json($packCard);
        }

        throw new NotFoundHttpException();
    }
}
