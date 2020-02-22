<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
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
     * UploaderHelper constructor.
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
            ->setDescription('Configure your environment dev to start build your blog')
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
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
        $envFile = $projectDir . '/.env.local';

        /*
         * Question of set in environment file.
         */
        $secretKey = $io->ask('Which secret key? ','f9c53050608c5d72494b951494c1ceb6ew312ewq');
        $nameDB = $io->ask('Which database name? ','blog_db');
        $userDB = $io->ask('Which database user? ','root');
        $passwordDB = $io->ask('Which database password? ','');
        $hostDB = $io->ask('Which database host? ','127.0.0.1:3306');
        $nameAPP = $io->ask('Which project name? ','Blog 1');
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
            $filesystem->remove($envFile);

            // Create file.
            if (!$filesystem->exists($envFile)) {
                $filesystem->touch($envFile);
                $filesystem->chmod($envFile, 0777);

                foreach($envContent as $line) {
                    $filesystem->appendToFile($envFile, $line . "\n");
                }
            }

            $io->success('Environment file successful created.');
        } catch (IOExceptionInterface $exception) {
            $io->error("Error creating environment file at: ". $exception->getPath());
        }

        /*
         * Execute necessary internal commands.
         */
        $commands = [
            'doctrine:database:create',
            'doctrine:migrations:diff',
            'doctrine:migrations:migrate',
            'doctrine:fixtures:load',
        ];

        $kernel = static::createKernel();
        $application = new Application($kernel);

        foreach($commands as $command) {
            $input = new ArrayInput([
                'command' => $command
            ]);

            $output = new BufferedOutput();
            $application->run($input, $output);
        }

        $io->success('Database successful created.');
        $io->note('Done.');
    }
}
