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
    $generatedLine = 0;
    $currentOriginalLine = debug_backtrace()[0]['line'];
    $currentOriginalFile = debug_backtrace()[0]['file'];
    $sourceStack = [];
    foreach(explode("\n", $css) as $line) {
        $mark = false;
        if (preg_match('~^/\*# (.+):([0-9]+|\$) \*/$~', $line, $matches)) {
            $mark = true;
            if ($matches[2] != '$') {
                array_push($sourceStack, [$currentOriginalFile, $currentOriginalLine]);
                $currentOriginalFile = $matches[1];
                $currentOriginalLine = $matches[2];
            }
            else {
                list($currentOriginalFile, $currentOriginalLine) = array_pop($sourceStack);
            }
        }
        else if (strlen($line)>3 && strstr($line, '{') !== false) {
            $mark = true;
        }
        if ($mark) {
            $map->addPosition([
                'generated' => [ 'line' => $generatedLine, 'column' => 1 ],
                'source'    => [ 'fileName' => str_replace(dirname(__FILE__) . '/', '', $currentOriginalFile), 'line' => $currentOriginalLine, 'column' => 1 ]
            ]);
        }
        $currentOriginalLine++;
        $generatedLine++;
    }
    $map->save('php://output');
    exit();
}