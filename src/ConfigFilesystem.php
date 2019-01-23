<?php

namespace Slidify;

use ErrorException;
use Symfony\Component\Filesystem\Filesystem;
class ConfigFilesystem
{
    const CONFIG_DIRECTORY = 'config';

    const CONFIG_FILENAME = 'auth.cfg';

    const GOOGLE_SERVICES_KEY = 'googleServiciesCredential.json';

    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    public function __get($property)
    {
        $config = $this->getConfigFile();

        if (!$config->{$property}) {
            throw new ErrorException("Config property {$property} doesn't exists");
        }

        return $config->{$property};
    }

    public function check() : bool
    {
        return !!$this->checkConfigDir()
            && $this->checkConfigFile();
    }

    public function checkGoogleCredentials()
    {
        return $this->filesystem->exists(
            $this->getConfigDir() . DIRECTORY_SEPARATOR . self::GOOGLE_SERVICES_KEY
        );
    }

    protected function checkConfigDir() : bool
    {
        return $this->filesystem->exists($this->getConfigDir());
    }

    protected function checkConfigFile() : bool
    {
        return $this->filesystem->exists($this->getConfigDir() . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME);
    }

    protected function getConfigDir() : string
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::CONFIG_DIRECTORY;
    }

    public function getConfigFile($arrayFormat = false)
    {
        return json_decode(
            file_get_contents($this->getConfigFilePath()),
            $arrayFormat
        );
    }

    public function getConfigFilePath() : string
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

    public function createGoogleApplicationsServicesKey(string $path)
    {
        return $this->filesystem->copy(
            $path,
            $this->getConfigDir() . DIRECTORY_SEPARATOR . self::GOOGLE_SERVICES_KEY,
            true
        );
    }

    protected function write($figmaAccessToken, $googleApiToken) : bool
    {
        $config = [
            'figmaAccessToken' => $figmaAccessToken,
            'googleApiToken' => $googleApiToken,
        ];

        return !!file_put_contents($this->getConfigFilePath(), json_encode($config));
    }
}