<?php

namespace Slidify\Console;

use Symfony\Component\Filesystem\Filesystem;

class ConfigFilesystem
{
    const CONFIG_DIRECTORY = 'config';

    const CONFIG_FILENAME = 'auth.cfg';

    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    public function check() : bool
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

    protected function bootstrap() : void
    {
        $this->filesystem->mkdir($this->getConfigDir());

        $this->filesystem->touch($this->getConfigDir() . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME);
    }

    public function create($figmaAccessToken, $googleApiToken)
    {
        if (!$this->check()) {
            $this->bootstrap();
        }

        $this->write($figmaAccessToken, $googleApiToken);

        return true;
    }

    protected function write($figmaAccessToken, $googleApiToken) : bool
    {
        $config = [
            'figmaAccessToken' => $figmaAccessToken,
            'googleApiToken' => $googleApiToken,
        ];

        return !!file_put_contents($this->getConfigFile(), json_encode($config));
    }
}