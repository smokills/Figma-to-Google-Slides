<?php

namespace Slidify\Console\Commands;

use RuntimeException;
use Slidify\Console\ConfigFilesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ConfigureCommand extends Command
{
    protected $config;

    public function __construct()
    {
        $this->config = new ConfigFilesystem;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('configure')
            ->setDescription('Configure the application')
            ->addOption('check', 'c', InputOption::VALUE_NONE, 'Check if the application have been configured')
            ->addOption('display', 'd', InputOption::VALUE_NONE, 'View the current application config')
            ->addArgument('figmaApiToken')
            ->addArgument('googleApiToken');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('check')) {
            return $this->config->check()
                ? $output->writeln('<info>### Application Configured ###</info>')
                : $output->writeln('<error>!!! Config file not found !!! </error>');
        }

        if (!$input->getArgument('figmaApiToken')) {
            throw new RuntimeException('Figma Api Token argument is required');
        }

        if (!$input->getArgument('googleApiToken')) {
            throw new RuntimeException('Google Api Token argument is required');
        }

        $output->writeln('<info>==> Creating config file..</info>');

        $this->config->create(
            $input->getArgument('figmaApiToken'),
            $input->getArgument('googleApiToken')
        );

        $output->writeln('<info>==> Application configured!</info>');
    }
}