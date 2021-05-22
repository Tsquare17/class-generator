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
 * Set the file name.
 */
$template->fileName('TestFile.php');


/**
 * Define the name used to fill placeholders.
 */
$template->name('TestFile');


/**
 * Define the contents of the file.
 */
$template->fileContent(<<<'FILE'
<?php

$foo = '{name}';
$bar = '{camel}';
$baz = '{pascal}';
$qux = '{underscore}';
$quux = '{dash}';
$quuz = '{name:plural}';
$customToken = '{foo_token}';
$quuuz = '{title}';
$quuuuz = '{underscore:plural:upper}';
FILE);


/**
 * Add a custom replacement token.
 */
$template->addReplacementToken('foo_token', function ($name) {
    return 'foo_value';
});
