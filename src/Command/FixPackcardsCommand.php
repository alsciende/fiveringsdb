<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:fix:packcards',
    description: 'Add a short description for your command',
)]
class FixPackcardsCommand extends Command
{
    use JsonTrait;

    public function __construct()
    {
        $this->fs = new Filesystem();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $finder = new Finder();
        $finder->directories()->in('./fixtures/pack_cards');
        foreach ($finder as $packDir) {
            $packId = $packDir->getFilename();
            $io->section($packId);

            $jsonFinder = new Finder();
            $jsonFinder->files()->in("./fixtures/pack_cards/{$packId}");
            foreach ($jsonFinder as $file) {
                $io->text($file->getFilename());
                $card = $this->getFileJsonContent($file->getRealPath());
                $card['pack_id'] = $packId;
                $this->putFileJsonContent($file->getRealPath(), $card);
            }
        }

        return Command::SUCCESS;
    }
}
