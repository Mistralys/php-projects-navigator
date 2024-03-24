<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use AppUtils\HTMLTag;

abstract class BaseProject
{
    public const ERROR_NOT_A_FILE = 153301;

    protected FolderInfo $folder;
    protected ?FileInfo $file = null;
    protected ?string $label = null;
    protected ProjectManager $manager;
    private string $relativePath;
    protected ?string $category = null;
    protected ?VCSType $vcsType = null;

    public function __construct(ProjectManager $manager, string $fileOrFolder)
    {
        $this->manager = $manager;
        $this->relativePath = $fileOrFolder;

        $path = $manager->getWebRootPath().'/'.$fileOrFolder;

        if(is_file($path)) {
            $this->file = FileInfo::factory($path);
            $this->folder = FolderInfo::factory(dirname($path));
            return;
        }

        $this->folder = FolderInfo::factory($manager->getWebRootPath().'/'.$fileOrFolder);
    }

    public function getID() : string
    {
        return str_replace(array('/', '\\'), '-', strtolower($this->relativePath));
    }

    public function getFolder() : FolderInfo
    {
        return $this->folder;
    }

    public function getFile() : ?FileInfo
    {
        return $this->file;
    }

    public function isFile() : bool
    {
        return isset($this->file);
    }

    public function requireFile() : FileInfo
    {
        if(isset($this->file)) {
            return $this->file;
        }

        throw new LPMException(
            'The project is a folder, not a file.',
            '',
            self::ERROR_NOT_A_FILE
        );
    }

    public function setLabel(string $label) : self
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel() : string
    {
        return $this->label ?? $this->folder->getName();
    }

    public function getLabelLinked() : string
    {
        return (string)HTMLTag::create('a')
            ->href($this->getURL())
            ->setContent($this->getLabel());
    }

    public function getURL() : string
    {
        return $this->manager->getWebRootURL().'/'.$this->relativePath;
    }

    public function setCategory(string $category) : self
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory() : string
    {
        return $this->category ?? '';
    }

    public function getLicense() : string
    {
        $composer = $this->getComposerInfo();
        if($composer !== null) {
            return $composer->getLicense();
        }

        return '';
    }

    public function getVCSType() : VCSType
    {
        if(!isset($this->vcsType)) {
            $this->vcsType = VCSTypes::getInstance()->detectType($this->folder);
        }

        return $this->vcsType;
    }

    public function getComposerInfo() : ?ComposerInfo
    {
        $file = $this->folder.'/composer.json';
        if(file_exists($file)) {
            return new ComposerInfo(JSONFile::factory($file));
        }

        return null;
    }

    public function getFilterText() : string
    {
        $parts = array(
            $this->getLabel(),
            $this->getCategory(),
            $this->getVCSType()->getLabel(),
            $this->getLicense()
        );

        $composer = $this->getComposerInfo();

        if($composer) {
            $parts[] = $composer->getPackageName();
            $parts[] = $composer->getPHPVersion();
        }

        $replaces = array(
            '/' => ' ',
            '\\' => ' ',
            '-' => ' ',
            '_' => ' '
        );

        return str_replace(
            array_keys($replaces),
            array_values($replaces),
            strtolower(implode(' ', $parts))
        );
    }
}
