<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\Clan;
use App\Repository\CardRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cards:get:clan',
    description: 'Returns a list of cards whose clan matches the argument',
)]
class GetCardsByFactionCommand extends Command
{
    use CardTableTrait;

    private SymfonyStyle $io;

    public function __construct(
        public CardRepository $cardRepository,
    ) {
        parent::__construct();
    }

    #[\Override]
    public function getStyle(): SymfonyStyle
    {
        return $this->io;
    }

    #[\Override]
    protected function configure(): void
    {
        $this
            ->addArgument('clan', InputArgument::REQUIRED, 'What field to look for.')
        ;
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $clanName = $input->getArgument('clan');

        // $io->info(
        //     sprintf(
        //         "clanName = $clanName\navailable clans : %s",
        //         implode(" ",
        //             array_map(
        //                 fn(Faction $clanName) => $clanName->value,
        //                 Faction::cases()
        //             )
        //         )
        //     )
        // );

        $clan = Clan::tryFrom($clanName);
        $cards = $this->cardRepository->findBy([
            'clan' => $clan,
        ]);

        if (empty($cards)) {
            $this->io->warning("No cards found with the provided clan '{$clanName}'");
        } else {
            $this->printCards($cards);
            $this->io->success('Done!');
        }

        return Command::SUCCESS;
    }
}
