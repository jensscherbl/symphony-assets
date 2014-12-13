<?php

class Extension_Assets extends Extension
{
    private $path = WORKSPACE . '/assets';

    // delegates

    public function getSubscribedDelegates()
    {
        return array(

            array('page'     => '/frontend/',
                  'delegate' => 'FrontendOutputPostGenerate',
                  'callback' => 'frontendOutputPostGenerate')
        );
    }

    // build assets

    public function frontendOutputPostGenerate()
    {
        // get build file

        $build_file = $this->path . '/build.php';

        if (!file_exists($build_file)) {

            throw new SymphonyErrorPage($build_file . ' missing.');
        }

        // get build timestamp

        $build_stat = stat($build_file);
        $build_time = $build_stat['mtime'];

        // get assets

        $assets = require($build_file);

        if (!is_array($assets)) {

            throw new SymphonyErrorPage($build_file . ' not an array.');
        }

        // get settings

        foreach ($assets as $type => $settings) {

            // get path

            $path = $this->path . '/' . $type;

            // get tasks

            if (!isset($settings['tasks']) || !is_array($settings['tasks'])) {

                $settings['tasks'] = array();
            }

            // get bundles

            if (!isset($settings['bundles']) || !is_array($settings['bundles'])) {

                throw new SymphonyErrorPage($build_file . ' fucked up.');
            }

            foreach ($settings['bundles'] as $bundle => $sources) {

                // get bundle file

                $bundle_file = $path . '/' . $bundle;

                // check if bundle exists

                if (!file_exists($bundle_file)) {

                    $rebuild = true;

                } else {

                    // get bundle timestamp

                    $bundle_stat = stat($bundle_file);
                    $bundle_time = $bundle_stat['mtime'];

                    // check freshness

                    if ($build_time > $bundle_time) {

                        $rebuild = true;
                    }
                }

                // get sources

                if (!is_array($sources)) {

                    throw new SymphonyErrorPage($build_file . ' fucked up.');
                }

                foreach ($sources as $key => $source) {

                    $sources[$key] = $path . '/' . $source;

                    if (!file_exists($sources[$key])) {

                        throw new SymphonyErrorPage($sources[$key] . ' missing.');
                    }

                    if (!$rebuild) {

                        // get source timestamp

                        $source_stat = stat($sources[$key]);
                        $source_time = $source_stat['mtime'];

                        // check freshness

                        if ($source_time > $bundle_time) {

                            $rebuild = true;
                        }
                    }
                }

                // check freshness

                if ($rebuild) {

                    // rebuild bundle

                    $this->compile($bundle_file, $sources, $settings['tasks']);

                    // reset freshness

                    unset($rebuild);
                }
            }
        }
    }

    // compile bundle

    private function compile($bundle_file, array $sources, array $tasks)
    {
        // concatenate sources

        $command = 'cat';

        foreach ($sources as $source) {

            $command = $command . ' ' . escapeshellarg($source);
        }

        // run tasks

        foreach ($tasks as $task) {

            $command = $command . ' | ' . escapeshellcmd($task);
        }

        // bundle file

        $command = $command . ' > ' . escapeshellarg($bundle_file);

        // execute

        exec($command);
    }
}
