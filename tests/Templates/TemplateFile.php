<?php

use Tsquare\FileGenerator\FileTemplate;
use Tsquare\FileGenerator\TokenAction;

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
 * Prepend the file contents.
 */
$template->prepend('<?php' . PHP_EOL . PHP_EOL);

/**
 * Define the contents of the file.
 */
$template->fileContent(
    <<<'FILE'
$foo = '{name}';
$bar = '{camel}';
$baz = '{pascal}';
$qux = '{underscore}';
$quux = '{dash}';
$quuz = '{name:plural}';
$customToken = '{foo_token}';
$quuuz = '{title}';
$quuuuz = '{underscore:plural:upper}';
$order = '{order_test_one:order_test_two:order_test_three}';
FILE
);


/**
 * Add a custom replacement token.
 */
$template->addReplacementToken(new TokenAction('foo_token', function ($name) {
    return 'foo_value';
}));

$template->addReplacementToken(new TokenAction('order_test_three', function ($name) {
    return '3';
}, 10));

$template->addReplacementToken(new TokenAction('order_test_two', function ($name) {
    return '2';
}, 1));

$template->addReplacementToken(new TokenAction('order_test_one', function ($name) {
    return '1';
}, 19));
