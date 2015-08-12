<?php

use Symphony\Extensions\Assets\Builder;

class Extension_Assets extends Extension
{
    private $configPath = '../workspace/assets.php';
    private $sourcePath = '../workspace/assets';
    private $targetPath = '../public/assets';

    // delegates

    public function getSubscribedDelegates()
    {
        return
        [[
            'page'     => '/frontend/',
            'delegate' => 'FrontendOutputPostGenerate',
            'callback' => 'frontendOutputPostGenerate'
        ]];
    }

    // handlers

    public function frontendOutputPostGenerate()
    {
        try {

            (new Builder($this->configPath, $this->sourcePath, $this->targetPath))->run();

        } catch (\Exception $exception) {

            throw new SymphonyErrorPage($exception->getMessage());
        }
    }
}
