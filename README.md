# FileGenerator

## Generate files using template config files.

### Installation
`composer require tsquare/file-generator`

### Usage:

##### Create a template file e.g., ./template-config/Example.php
```php
<?php

use Tsquare\FileGenerator\FileTemplate;
use Tsquare\FileGenerator\FileEditor;

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
$template->destinationPath(dirname(__DIR__, 1) . '/Sample');


/**
 * Define the name used to fill placeholders.
 */
$template->name('Example');


/**
 * Define the file name. If not defined, name will be used.
 */
$template->fileName('{name}File');


/**
 * A title can be set for content replacement purposes.
 */
$template->title('A Title');


/**
 * Define the contents of the file.
 */
$template->fileContent('
namespace App\Foo\{name};

$foo = \'{underscore}s\';
$bar = \'{dash}\';

function foo{name}() {
    return \'{title}\';
}

');


/*
 * Editing actions can be added, that will be used if the file already exists.
 */
$editor = new FileEditor();

$editor->insertBefore('
function inserted{pascal}Function() {
    return true;
}

', 'function foo{name}()');

$editor->insertAfter('
function another{pascal}Function()  {
    return true;
}
', 'function foo{name}() {
        return true;
    }
');

$editor->replace('another{pascal}Function', 'someOther{pascal}Function');

$template->ifFileExists($editor);
```

##### Initialize FileTemplate with the path to the template file, pass it to FileGenerator, and call create.
```php
<?php

use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

$template = FileTemplate::init(__DIR__ . '/template-config/Example.php');

$generator = new FileGenerator($template);

$generator->create();
```

##### The following template replace tokens are available.
```
{name}        : ExampleName
{camel}       : exampleName
{pascal}      : ExampleName
{underscore}  : example_name
{dash}        : example-name
{name:plural} : ExampleNames
{title}       : A Title
```
