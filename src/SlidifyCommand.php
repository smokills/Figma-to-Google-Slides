<?php

namespace Slidify\Console;

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

class SlidifyCommand extends Command
{
    public function configure()
    {
        $this->setName('export')
            ->setDescription('Export Figma frames into a Google Slides presentation');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}