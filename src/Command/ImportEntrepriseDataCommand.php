<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:import-entreprise-data',
    description: 'Importation des données de entreprise.sql dans la base de données',
)]
class ImportEntrepriseDataCommand extends Command
{
    public function __construct(private Connection $connection, private KernelInterface $kernel)
    {
        parent::__construct();
    }

//    protected function configure(): void
//    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
//    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sqlFilePath = "{$this->kernel->getProjectDir()}/public/.sql/entreprise.sql";
        //dd($sqlFilePath);

        if (!file_exists($sqlFilePath)){
            $io->error('SQL file not found.');
            return Command::FAILURE;
        }

        $sql = file_get_contents($sqlFilePath);
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();

        if ($result) {
            $io->success('Enterprise data imported successfully.');
            return Command::SUCCESS;
        }

        $io->error('Failed to import enterprise data.');
        return Command::FAILURE;
    }
}
