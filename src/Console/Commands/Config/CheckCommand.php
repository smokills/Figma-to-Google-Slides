<?php

namespace Slidify\Console\Commands\Config;

use RuntimeException;
use Slidify\ConfigFilesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected $config;

    public function __construct()
    {
        $this->config = new ConfigFilesystem;

        parent::__construct();
    }
    public function configure()
    {
        $this->setName('config:check')
            ->setDescription('Check if the application have been configured');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->config->check()
                ? $output->writeln('<info>### Application Configured ###</info>')
                : $output->writeln('<error>!!! Config file not found !!! </error>');
    }
}