<?php

namespace Slidify\Console\Commands\Config;

use RuntimeException;
use Slidify\ConfigFilesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeCommand extends Command
{
    protected $config;

    public function __construct()
    {
        $this->config = new ConfigFilesystem;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('config:make')
            ->setDescription('Configure the application');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->config->check()) {
            $this->setupAccessTokens($input, $output);
        } else {
            $output->writeln('<info>==> Application tokens already configured!</info>');
        }

        if (!$this->config->checkGoogleCredentials()) {
            $this->setupGoogleCredentials($input, $output);
        } else {
            $output->writeln('<info>==> Google Credentials already configured!</info>');
        }
    }

    public function setupAccessTokens(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('question');

        $figmaAccessToken = $dialog->ask(
            $input,
            $output,
            new Question('<question>Enter the Figma Access Token</question>' . PHP_EOL)
        );

        $googleApiToken = $dialog->ask(
            $input,
            $output,
            new Question('<question>Enter the Google Api Token</question>' . PHP_EOL)
        );

        $output->writeln('<info>==> Creating config file...</info>');

        $this->config->create(
            $figmaAccessToken,
            $googleApiToken
        );

        $output->writeln('<info>==> Application configured!</info>');
    }

    public function setupGoogleCredentials(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('question');

        $googleCredentialsPath = $dialog->ask(
            $input,
            $output,
            new Question('<question>Enter the Path of the Google Servicies json file. Note: the path must be absolute!</question>' . PHP_EOL)
        );

        return $this->config->createGoogleApplicationsServicesKey($googleCredentialsPath);
    }
}