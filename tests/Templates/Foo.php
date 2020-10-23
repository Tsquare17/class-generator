<?php

use Tsquare\FileGenerator\FileTemplate;

/**
 * @var FileTemplate $template
 */


/**
 * Define the application root.
 */
$template->appBasePath(dirname(__DIR__, 1));


/**
 * Define the base path for the file.
 */
$template->destinationPath('/Fixtures');


/**
 * Define the name used to fill placeholders.
 */
$template->name('Foo');


/**
 * Define the contents of the file.
 */
$template->fileContent(
    '
namespace Fixtures;

class {name}
{
    public function {underscore}(): {name}
    {
        return $this;
    }
}
'
);
