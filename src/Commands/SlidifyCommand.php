<?php

namespace Slidify\Console\Commands;

use RuntimeException;
use GuzzleHttp\Client;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Slidify\Console\ConfigFilesystem;

class SlidifyCommand extends Command
{
    protected $config;

    public function __construct()
    {
        $this->config = new ConfigFilesystem;

        parent::__construct();
    }
    public function configure()
    {
        $this->setName('export')
            ->setDescription('Export Figma frames into a Google Slides presentation');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->config->check()) {
            throw new RuntimeException('Config file not found. Please run configure command');
        }
    }
}