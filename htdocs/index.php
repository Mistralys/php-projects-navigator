<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\JSONFile;

$configFile = __DIR__ . '/../config/projects.json';
if(!file_exists($configFile)) {
    die('Please create the project configuration file in the <code>config</code> folder.');
}

$autoloader = __DIR__.'/../vendor/autoload.php';
if(!file_exists($autoloader)) {
    die('Please run the <code>composer install</code> command to set up the necessary dependencies.');
}

require_once $autoloader;

$config = ArrayDataCollection::create(JSONFile::factory($configFile)->parse());

$pm = ProjectManager::factory(
    $config->getString('webRootURL'),
    $config->getString('webRootPath'),
    $config->getString('navigatorFolder')
);

foreach($config->getArray('projectFolders') as $folder) {
    $pm->addProjectsFolder($folder['folder'], $folder['label']);
}

foreach($config->getArray('tools') as $tool) {
    $pm->addTool(
        $tool['label'],
        $tool['folder'] ?? $tool['file']
    )
        ->showInMainNav($tool['inMainNav'] ?? false);
}

$pm->display();
