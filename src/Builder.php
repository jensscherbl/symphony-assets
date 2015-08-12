<?php

namespace Symphony\Extensions\Assets;

class Builder
{
    private $configPath;
    private $sourcePath;
    private $targetPath;
    private $assets;

    public function __construct($configPath, $sourcePath, $targetPath)
    {
        $this->configPath = $configPath;
        $this->sourcePath = $sourcePath;
        $this->targetPath = $targetPath;
        $this->assets     = [];

        if (!is_file($this->configPath)) {

            throw new \Exception(

                "({$this->configPath}) is missing."
            );
        }

        require $this->configPath;
    }

    // run asset builder

    public function run()
    {
        // get config timestamp

        $configTime = filemtime($this->configPath);

        foreach ($this->assets as $asset => $config) {

            $target = $this->targetPath . '/' . $asset;

            if (!is_file($target)) {

                $build = true;

            } else {

                // get target timestamp

                $targetTime = filemtime($target);

                // check target freshness

                if ($configTime > $targetTime) {

                    $build = true;
                }
            }

            // check files

            if (!isset($config->files) || !is_array($config->files)) {

                throw new \Exception(

                    "({$asset}) has no source files."
                );
            }

            foreach ($config->files as $key => $file) {

                $config->files[$key] = $this->sourcePath . '/' . $file;

                if (!is_file($config->files[$key])) {

                    throw new \Exception(

                        "({$config->files[$key]}) is missing."
                    );
                }

                if (isset($build)) {

                    continue;
                }

                // get file timestamp

                $fileTime = filemtime($config->files[$key]);

                // check target freshness

                if ($fileTime > $targetTime) {

                    $build = true;
                }
            }

            if (!isset($build)) {

                continue;
            }

            // check tasks

            if (!isset($config->tasks) || !is_array($config->tasks)) {

                $config->tasks = [];
            }

            // build asset

            $this->build($target, $config->files, $config->tasks);
        };
    }

    // add assets

    private function add($asset, callable $callback)
    {
        $this->assets[$asset] = $callback(new \stdClass);
    }

    // build assets

    private function build($target, array $files, array $tasks)
    {
        // concatenate files

        $command = 'cat';

        foreach ($files as $file) {

            $command = $command . ' ' . escapeshellarg($file);
        }

        // run tasks

        foreach ($tasks as $task) {

            $command = $command . ' | ' . escapeshellcmd($task);
        }

        // bundle file

        $command = $command . ' > ' . escapeshellarg($target);

        // execute

        exec($command);
    }
}
