<?php

declare(strict_types=1);

namespace Mistralys\LPM;

use function AppLocalize\pt;

$manager = ProjectManager::getInstance();
$appURL = $manager->getManagerURL();

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php pt('Project Manager'); ?></title>
    <link rel="canonical" href="<?php echo $appURL ?>">
    <link href="<?php echo $appURL ?>/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $appURL ?>/htdocs/css/main.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo $manager->getName() ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <?php
            $tools = $manager->getTools();
            $subNav = array();
            $mainNav = array();
            foreach($tools as $tool) {
                if(!$tool->isInMainNav()) {
                    $subNav[] = $tool;
                } else {
                    $mainNav[] = $tool;
                }
            }

            ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                foreach($mainNav as $tool)
                {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $tool->getURL() ?>"><?php echo $tool->getLabel() ?></a>
                    </li>
                    <?php
                }

                if(!empty($subNav))
                {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php pt('Tools'); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach($subNav as $tool)
                        {
                            ?>
                            <li><a class="dropdown-item" href="<?php echo $tool->getURL() ?>"><?php echo $tool->getLabel() ?></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
                }
                ?>
            </ul>
            <form class="d-flex" role="search">
                <input id="elSearchTerms" class="form-control me-2" placeholder="<?php pt('Type to filter...'); ?>" aria-label="Search" onkeyup="filters.Apply()" onchange="filters.Apply()" onreset="filters.Apply()">
                <button class="btn btn-outline-warning" type="button" onclick="filters.Reset()" title="<?php pt('Reset the filters'); ?>">X</button>
            </form>
        </div>
    </div>
</nav>

<main class="container">
    <h1><?php pt('Available projects') ?></h1>
    <br>
    <table class="table table-hover" id="projects">
        <thead>
        <tr>
            <th scope="col"><?php pt('Name') ?></th>
            <th scope="col"><?php pt('Category') ?></th>
            <th scope="col"><?php pt('VCS') ?></th>
            <th scope="col"><?php pt('Composer project') ?></th>
            <th scope="col"><?php pt('License'); ?></th>
            <th scope="col"><?php pt('PHP'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $projects = $manager->getProjects();
        foreach ($projects as $project)
        {
            $composer = $project->getComposerInfo();

            ?>
            <tr data-filter-text="<?php echo $project->getFilterText() ?>">
                <td><?php echo $project->getLabelLinked() ?></td>
                <td><?php echo $project->getCategory() ?></td>
                <td><?php echo $project->getVCSType()->getLabel() ?></td>
                <td><?php if($composer) { echo $composer->getNameLinked(); } ?></td>
                <td><?php echo $project->getLicense() ?></td>
                <td><?php if($composer) { echo $composer->getPHPVersion(); } ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</main>
<script src="<?php echo $appURL ?>/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $appURL ?>/htdocs/js/filtering.js"></script>
<script>
    const filters = new Filtering('#projects tbody tr', '#elSearchTerms');
</script>
</body>
</html>
