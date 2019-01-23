<?php

namespace Slidify\Console;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ConfigureCommand extends Command
{
    protected $filesystem;

    const CONFIG_DIRECTORY = 'config';

    const CONFIG_FILENAME = 'auth.cfg';

    public function __construct()
    {
        $this->filesystem = new Filesystem;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('configure')
            ->setDescription('Configure the application')
            ->addOption('check', 'c')
            ->addArgument('figmaApi')
            ->addArgument('googleApi');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('check')) {
            $this->check() ?
                $output->writeln('Application Configured') :
                $output->writeln('Config file not found');
            die;
        }

        $this->createConfig(
            $input->getArgument('figmaApi'),
            $input->getArgument('googleApi')
        );
    }

    protected function createConfig($figmaAccessToken, $googleApiToken)
    {
        if (!$this->check()) {
            $this->bootstrapConfig();
        }

        $this->writeToConfig($figmaAccessToken, $googleApiToken);

        return true;
    }

    protected function check() : bool
    {
        return !!$this->checkConfigDir()
            && $this->checkConfigFile();
    }

    protected function checkConfigDir() : bool
    {
        return !!$this->filesystem->exists($this->getConfigDir());
    }

    protected function checkConfigFile() : bool
    {
        return !!$this->filesystem->exists($this->getConfigDir() . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME);
    }

    protected function getConfigDir() : string
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::CONFIG_DIRECTORY;
    }

    protected function getConfigFile() : string
    {
        return $this->getConfigDir() . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME;
    }

    protected function bootstrapConfig() : void
    {
        $this->filesystem->mkdir($this->getConfigDir());

        $this->filesystem->touch($this->getConfigDir() . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME);
    }

    protected function writeToConfig($figmaAccessToken, $googleApiToken) : bool
    {
        $config = [
            'figmaAccessToken' => $figmaAccessToken,
            'googleApiToken' => $googleApiToken,
        ];

        return !!file_put_contents($this->getConfigFile(), json_encode($config));
    }
}