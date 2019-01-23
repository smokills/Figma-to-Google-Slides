<?php

namespace Slidify;

use Google_Client;
use Google_Service_Slides;
use RuntimeException;
use Slidify\Console\ConfigFilesystem;

class GoogleSlidesClient
{
    const APPLICATION_NAME = 'Figma_Exporter';

    private $config;

    private $googleClient;

    public function __construct() {
        $this->config = new ConfigFilesystem;

        $this->googleClient = $this->bootGoogleClient();
    }

    protected function bootGoogleClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(self::APPLICATION_NAME);
        $client->setScopes(Google_Service_Slides::PRESENTATIONS);
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->setAccessType('offline');
        $client->setAuthConfig(__DIR__ . '/SERVICE_KEY.json');

        $service = new Google_Service_Slides($client);
    }
}