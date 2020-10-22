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
$template->destinationPath(dirname(__DIR__, 1) . '/Templates/Destination');


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
$customToken = \'{foo_token}\';
');


/**
 * Add a custom replacement token.
 */
$template->addReplacementToken('{foo_token}', 'foo_value');
