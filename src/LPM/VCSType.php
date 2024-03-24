<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\Interfaces\StringPrimaryRecordInterface;

class VCSType implements StringPrimaryRecordInterface
{
    private string $id;
    private string $label;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isType(string $type) : bool
    {
        return $this->id === $type;
    }

    public function isGit() : bool
    {
        return $this->isType(VCSTypes::TYPE_GIT);
    }

    public function isSVN() : bool
    {
        return $this->isType(VCSTypes::TYPE_SVN);
    }

    public function isNone() : bool
    {
        return $this->isType(VCSTypes::TYPE_NONE);
    }
}
