<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class BlogBuildDevCommand extends Command
{
    protected static $defaultName = 'blog:build:dev';

    /**
     * @var String
     */
    private $projectDir;

    /**
     * BlogBuildDevCommand constructor.
     *
     * @param String $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Configure your environment dev and database to start build your blog')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /*
         * Create environment file.
         */
        $filesystem = new Filesystem();
        $projectDir = $this->projectDir;
        $envLocal = $projectDir . '/.env.local';

        /*
         * Question of set in environment file.
         */
        $secretKey = $io->ask('Which secret key? ','f9c53050608c5d72494b951494c1ceb6ew312ewq');
        $nameDB = $io->ask('Which database name? ','blog_db');
        $userDB = $io->ask('Which database user? ','root');
        $passwordDB = $io->ask('Which database password? ','');
        $hostDB = $io->ask('Which database host? ','127.0.0.1:3306');
        $nameAPP = $io->ask('Which project name? ','Blog');
        $emailAPP = $io->ask('Which your email? ','example@email.com');

        $envContent = [
            '# Config',
            'APP_ENV=dev',
            'APP_SECRET=' . $secretKey,
            'DATABASE_URL=mysql://' . $userDB  . ':' . $passwordDB . '@' . $hostDB . '/' . $nameDB,
            '',
            '# Costum variables',
            'APP_NAME=\'' . $nameAPP.'\'',
            'ADMIN_NAME=\'Admin Project\'',
            'ADMIN_NAME_SIGLA=\'AP\'',
            'EMAIL=\'' . $emailAPP.'\'',
            'BASE_URL=\'http://localhost:8000\'',
            'APP_LOCALES=\'en\'',
        ];

        try {
            // Delete if exist.
            $filesystem->remove($envLocal);

            // Create file.
            if (!$filesystem->exists($envLocal)) {
                $filesystem->touch($envLocal);
                $filesystem->chmod($envLocal, 0777);

                foreach($envContent as $line) {
                    $filesystem->appendToFile($envLocal, $line . "\n");
                }
            }

            $io->success('Environment file successful created.');
        } catch (IOExceptionInterface $exception) {
            $io->error("Error creating environment file at: ". $exception->getPath());
        }

        /*
         * Execute necessary internal commands for create your database.
         */
        $commands = [
            'doctrine:database:create',
            'doctrine:migrations:diff',
            'doctrine:migrations:migrate',
            'doctrine:fixtures:load',
        ];

        foreach($commands as $command) {
            $command = $this->getApplication()->find($command);

            $arguments = [
                'command' => $command,
            ];

            $greetInput = new ArrayInput($arguments);
            $command->run($greetInput, $output);
        }

        $io->success('Database successful created.');

        $output->writeln('Your default access for /admin:');
        $output->writeln('Login: admin');
        $output->writeln('Password: admin');

        $io->note('Done.');
    }
}
