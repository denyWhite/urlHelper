<?php
declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use voku\PhpReadmeHelper\GenerateApi;

$readmeGenerator = new GenerateApi();
$readmeGenerator->hideTheFunctionIndex = true;

$readmeGenerator->templateMethod = <<<RAW

#### %name%
%description%

**Parameters:**
%params%

**Return:**
%return%

--------
RAW;



$readmeText = ($readmeGenerator)->generate(
    __DIR__ . '/../src/Url.php',
    __DIR__ . '/../build/docs/base.md'
);

file_put_contents(__DIR__ . '/../README.md', $readmeText);