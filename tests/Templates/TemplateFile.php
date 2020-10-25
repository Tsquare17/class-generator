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
$template->destinationPath('/Templates/Destination');


/**
 * Define the name used to fill placeholders.
 */
$template->name('TestFile');


$template->title('A Title');


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
$customToken = \'{foo_token}\';
$quuuz = \'{title}\';
$quuuuz = \'{underscore:plural:upper}\';
');


/**
 * Add a custom replacement token.
 */
$template->addReplacementToken('foo_token', function ($name) {
    return 'foo_value';
});
