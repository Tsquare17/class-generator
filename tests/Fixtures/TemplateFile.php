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
$template->destinationPath('/Fixtures/Destination');


/**
 * Define the name used to fill placeholders.
 */
$template->name('TestFile');


/**
 * Define the contents of the file.
 */
$template->fileContent('
$foo = \'{name}\';
$bar = \'{camel}\';
$baz = \'{pascal}\';
$qux = \'{underscore}\';
$quux = \'{dash}\';
$quuz = \'{name:plural}\';
');
