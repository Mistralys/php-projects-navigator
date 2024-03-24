<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper_Exception;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;
use function AppLocalize\t;

class ProjectManager implements  RenderableInterface
{
    use RenderableBufferedTrait;

    public const ERROR_NO_INSTANCE_CREATED = 153201;

    /**
     * @var array<string,string>
     */
    private array $projectFolders = array();

    /**
     * @var array<string,BaseProject>
     */
    private array $projects = array();

    /**
     * @var Tool[]
     */
    private array $tools = array();
    private static ?ProjectManager $instance = null;
    private string $webRootURL;
    private FolderInfo $webRootPath;
    private bool $detected = false;
    private string $managerFolder;

    private function __construct(string $webRootURL, string $webRootPath, string $managerFolder)
    {
        $this->webRootURL = $webRootURL;
        $this->webRootPath = FolderInfo::factory($webRootPath);
        $this->managerFolder = $managerFolder;
    }

    public static function factory(string $webRootURL, string $webRootPath, string $managerFolder) : ProjectManager
    {
        if(!isset(self::$instance)) {
            self::$instance = new ProjectManager($webRootURL, $webRootPath, $managerFolder);
        }

        return self::$instance;
    }

    /**
     * @return ProjectManager
     * @throws LPMException
     */
    public static function getInstance() : ProjectManager
    {
        if(isset(self::$instance)) {
            return self::$instance;
        }

        throw new LPMException(
            'No instance has been created yet.',
            sprintf(
                'You must call %s::%s() first.',
                self::class,
                array(self::class, 'factory')[1]
            ),
            self::ERROR_NO_INSTANCE_CREATED
        );
    }

    public function getName() : string
    {
        return t('Project Navigator');
    }

    public function getWebRootPath(): FolderInfo
    {
        return $this->webRootPath;
    }

    public function getWebRootURL(): string
    {
        return $this->webRootURL;
    }

    public function getManagerURL() : string
    {
        return $this->getWebRootURL().'/'.$this->managerFolder;
    }

    public function generateOutput() : void
    {
        include __DIR__.'/../../templates/overview.php';
    }

    /**
     * @param string $relativePath
     * @param string $label
     * @return $this
     */
    public function addProjectsFolder(string $relativePath, string $label) : self
    {
        $this->projectFolders[$relativePath] = $label;
        return $this;
    }

    /**
     * @param string $label
     * @param string $relativePath
     * @return Tool
     */
    public function addTool(string $label, string $relativePath) : Tool
    {
        $tool = new Tool($this, $label, $relativePath);
        $this->tools[] = $tool;
        return $tool;
    }

    public function addProject(string $folder) : Project
    {
        $project = new Project($this, $folder);
        $this->registerProject($project);
        return $project;
    }

    private function registerProject(BaseProject $project) : void
    {
        $this->projects[$project->getID()] = $project;
    }

    /**
     * @return BaseProject[]
     * @throws FileHelper_Exception
     */
    public function getProjects() : array
    {
        $this->detectProjects();

        return array_values($this->projects);
    }

    /**
     * @return void
     * @throws FileHelper_Exception
     */
    private function detectProjects() : void
    {
        if($this->detected) {
            return;
        }

        $this->detected = true;

        foreach($this->projectFolders as $relative => $label) {
            $this->detectProjectsInFolder($relative, $label);
        }

        uasort($this->projects, static function(BaseProject $a, BaseProject $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    /**
     * @param string $relative
     * @param string $label
     * @return void
     * @throws FileHelper_Exception
     */
    private function detectProjectsInFolder(string $relative, string $label) : void
    {
        $path = FolderInfo::factory($this->webRootPath.'/'.$relative);
        $subFolders = $path->getSubFolders();

        foreach($subFolders as $subFolder) {
            $this->addProject($relative.'/'.$subFolder->getName())
                ->setCategory($label);
        }
    }

    /**
     * @return Tool[]
     */
    public function getTools() : array
    {
        return $this->tools;
    }
}
