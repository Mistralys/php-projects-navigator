<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\FolderInfo;
use function AppLocalize\t;


/**
 * @method VCSType getByID(string $id)
 * @method VCSType[] getAll()
 * @method VCSType getDefault()
 */
class VCSTypes extends BaseStringPrimaryCollection
{
    public const TYPE_GIT = 'git';
    public const TYPE_SVN = 'svn';
    public const TYPE_NONE = 'none';

    private static ?VCSTypes $instance = null;

    public static function getInstance() : VCSTypes
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getDefaultID(): string
    {
        return self::TYPE_NONE;
    }

    protected function registerItems(): void
    {
        $this->registerItem(new VCSType(self::TYPE_GIT, t('Git')));
        $this->registerItem(new VCSType(self::TYPE_SVN, t('Subversion')));
        $this->registerItem(new VCSType(self::TYPE_NONE, t('None')));
    }

    public function detectType(FolderInfo $sourcesFolder) : VCSType
    {
        if(file_exists($sourcesFolder.'/.git')) {
            return $this->getByID(self::TYPE_GIT);
        }

        if(file_exists($sourcesFolder.'/.svn')) {
            return $this->getByID(self::TYPE_SVN);
        }

        return $this->getDefault();
    }
}
