<?php
require_once __DIR__ . '/../vendor/autoload.php';

$pygmentizePath = getenv('PYGMENTIZE_PATH');

if (!$pygmentizePath) {
    fwrite(
        STDERR,
        'ERROR: You must provide a PYGMENTIZE_PATH environment variable in '
        . 'your phpunit.xml configuration.' . "\n"
    );
    exit(1);
}

$output = [];
$returnValue = 1;

@exec(escapeshellcmd($pygmentizePath . ' -V'), $output, $returnValue);

if ($returnValue !== 0) {
    fwrite(
        STDERR,
        sprintf(
            "An error occurred while attempting to execute `%s -V`.\n",
            $pygmentizePath
        )
    );
    exit(1);
}

$pygmentizeVersion = array_shift($output);
if (preg_match('/(\d+\.\d+)(\.\d+)?/', $pygmentizeVersion, $matches) === 0) {
    fwrite(
        STDERR,
        sprintf(
            "Unable to find a supported version of Pygments at %s.\n",
            $pygmentizePath
        )
    );
    exit(1);
}

if (!file_exists(__DIR__ . '/Resources/pygments-' . $matches[1])) {
    fwrite(
        STDERR,
        sprintf(
            "No test Resources directory found for Pygments version %s.\n",
            $matches[1]
        )
    );
    exit(1);
}

putenv("PYGMENTIZE_VERSION={$matches[1]}");
