# PHP Projects Navigator

This lightweight tool is meant to be used as your local webserver's starting page.
It lists all your projects and tools in a simple and clean UI. 

## Features

- Find and list all project folders
- Filter the list by search terms
- Detect VCS type (Git, SVN...)
- Link to Composer project homepages
- Display PHP version constraints
- Register tools for quick access in the UI

## Requirements

- Local webserver with PHP support
- PHP 7.4 or higher
- [Composer][]

## Installation

1. Clone this repository to a location on your webserver.
2. Run `composer install` in the project directory.
3. Copy the file `config/projects.json` to `projects.json`.
4. Edit the config file to match your project structure.
5. Open the UI in your browser, e.g. `http://localhost/php-projects-navigator/htdocs/`.
6. (Optional) Set as your webserver's homepage, see [Use as Webserver homepage](#use-as-webserver-homepage).

### Use as Webserver homepage

Because all paths and URLs are absolute, the UI can be used from any subdirectory of the webserver.

1. Create an `index.php` file in the target location.
2. Include the `htdocs/index.php` file of this project there.

Example code:

```php
<?php include __DIR__.'/tools/php-projects-navigator/htdocs/index.php';
```

## The projects.json file

The base working principle is that all project folders and tools are
assumed to be sub-folders of your webserver's document root. 
The absolute paths are generated from the document root and the relative
path specified in the configuration file.


[Composer]: https://getcomposer.org/ 