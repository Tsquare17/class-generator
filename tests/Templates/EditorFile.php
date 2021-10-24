<?php

use Tsquare\FileGenerator\FileEditor;
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
 * Set the file name.
 */
$template->fileName('OnlyEdit.php');


/**
 * Define the name used to fill placeholders.
 */
$template->name('Testing');


$editor = new FileEditor();

$editor->insertAfter(
    <<<'FILE'

        $custom = '{custom}';
FILE
    , '/\$test.+test.+;/'
)->ifNotContaining('/something-non-existent/')
    ->isRegex();

$template->fileEditor($editor);
