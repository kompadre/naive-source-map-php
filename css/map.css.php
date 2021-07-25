<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use axy\sourcemap\SourceMap;

/**
* @param string $originalUrl
 */
function renderSourceMap($originalUrl='') {
    if ($originalUrl == '') {
        $protocolPart = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '');
        $originalUrl =  $protocolPart . '://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SCRIPT_NAME'];
    }
    $css = file_get_contents($originalUrl . '?debug=true' );
    $map = new SourceMap(null, 'css.php');
    $map->sourceRoot = '/css';
    $map->file = 'css.php';
    $originalLine = 1;
    foreach(explode("\n", $css) as $line) {
        if (preg_match('~^/\*# (.+):([0-9]+) \*/$~', $line, $matches)) {
            $map->addPosition([
                'generated' => [ 'line' => $originalLine, 'column' => 1 ],
                'source'    => [ 'fileName' => str_replace(dirname(__FILE__) . '/', '', $matches[1]), 'line' => $matches[2], 'column' => 1 ]
            ]);
        }
        $originalLine++;
    }
    $map->save('php://output');
    exit();
}

function _map_hint() {
    if (defined('__DEBUG')) {
        $bt = debug_backtrace();
        echo '/*# '. str_replace(__DIR__, '', $bt[0]['file']) . ':' . $bt[0]['line'] .' */' . PHP_EOL;
    }
}