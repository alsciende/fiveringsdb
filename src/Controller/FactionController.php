<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\Clan;
use App\Form\SimpleCardSearchType;
use App\Search\SimpleCardSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FactionController extends AbstractController
{
    #[Route('/clan/{clan}', name: 'app_clan')]
    public function index(Clan $clan, TranslatorInterface $translator): Response
    {
        return $this->forward('\App\Controller\SearchController::search', [
            'form' => $this->createForm(SimpleCardSearchType::class, new SimpleCardSearch('f:' . $clan->value), [
                'action' => $this->generateUrl('app_search_cards'),
                'method' => 'GET',
            ]),
            'title' => $translator->trans($clan->value, [], 'clans'),
        ]);
    }
}
