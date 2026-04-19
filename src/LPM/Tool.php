<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use function AppLocalize\t;

class Tool extends BaseProject
{
    private bool $inMainNav = false;
    private ?string $customURL = null;

    public function __construct(ProjectManager $manager, string $label, string $folder = '')
    {
        if($folder !== '') {
            parent::__construct($manager, $folder);
        } else {
            $this->manager = $manager;
        }

        $this->setLabel($label);
    }

    public function setCustomURL(string $url) : self
    {
        $this->customURL = $url;
        return $this;
    }

    public function getURL() : string
    {
        if($this->customURL !== null) {
            return $this->customURL;
        }

        return parent::getURL();
    }

    public function getCategory(): string
    {
        return t('Tools');
    }

    public function showInMainNav(bool $enabled=true) : self
    {
        $this->inMainNav = $enabled;
        return $this;
    }

    public function isInMainNav() : bool
    {
        return $this->inMainNav;
    }
}
