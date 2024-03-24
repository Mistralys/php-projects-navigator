<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\JSONFile;
use AppUtils\HTMLTag;

class ComposerInfo
{
    private JSONFile $configFile;
    private ArrayDataCollection $data;
    private bool $loaded = false;

    public function __construct(JSONFile $config)
    {
        $this->configFile = $config;
        $this->data = new ArrayDataCollection();
    }

    public function getPackageName() : string
    {
        $this->load();
        return $this->data->getString('name');
    }

    public function getNameLinked() : string
    {
        return (string)HTMLTag::create('a')
            ->href($this->getHomepage())
            ->attr('target', '_blank')
            ->setContent($this->getPackageName());
    }

    public function getHomepage() : string
    {
        $url = $this->data->getString('homepage');
        if(!empty($url)) {
            return $url;
        }

        return 'https://github.com/'.$this->getPackageName();
    }

    public function getPHPVersion() : string
    {
        $requires = $this->getRequires();
        foreach($requires as $package => $version) {
            if($package === 'php') {
                return $version;
            }
        }

        return '';
    }

    public function getLicense() : string
    {
        $this->load();
        return $this->data->getString('license');
    }

    /**
     * @return array<string,string>
     */
    public function getRequires() : array
    {
        $this->load();
        return $this->data->getArray('require');
    }

    private function load() : void
    {
        if($this->loaded) {
            return;
        }

        $this->loaded = true;

        $this->data->setKeys($this->configFile->parse());
    }
}
