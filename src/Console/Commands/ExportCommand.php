<?php

namespace Slidify\Console\Commands;

use Slidify\ConfigFilesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends Command
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
            ->setDescription('Export Figma frames into a Google Slides presentation')
            ->addArgument(
                'figma-file-id',
                InputArgument::REQUIRED,
                'The Figma File ID to export'
            )->addArgument(
                'presentation-id',
                InputArgument::REQUIRED,
                'The Google Sheets presentation ID'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->config->check()) {
            throw new RuntimeException('Config file not found. Please run configure command');
        }

        $googleClient = $this->bootGoogleClient();
    }

    protected function bootGoogleClient()
    {
        return new GoogleSlidesClient;
    }
}