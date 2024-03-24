<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use function AppLocalize\t;

class Tool extends BaseProject
{
    private bool $inMainNav = false;

    public function __construct(ProjectManager $manager, string $label, string $folder)
    {
        parent::__construct($manager, $folder);

        $this->setLabel($label);
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
