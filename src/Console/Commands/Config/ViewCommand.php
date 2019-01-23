<?php

namespace Slidify\Console\Commands\Config;

use RuntimeException;
use Slidify\ConfigFilesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ViewCommand extends Command
{
    protected $config;

    public function __construct()
    {
        $this->config = new ConfigFilesystem;

        parent::__construct();
    }
    public function configure()
    {
        $this->setName('config:view')
            ->setDescription('View the current application config');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->config->check()) {
            throw new RuntimeException('Config file not found. Please run configure config:make');
        }

        $table = new Table($output);

        $table->setHeaders(['Figma Api Token', 'Google Api Token'])
            ->setRows([
                [
                    $this->config->figmaAccessToken,
                    $this->config->googleApiToken
                ]
            ]);

        $table->render();
    }
}