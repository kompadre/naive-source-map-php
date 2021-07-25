<?php
use axy\sourcemap\SourceMap;
require_once(__DIR__ . '/../vendor/autoload.php');
if (isset($_GET['map']) && $_GET['map'] == 'true') {
    $protocolPart = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '');
    $baseUrl =  $protocolPart . '://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SCRIPT_NAME'];
    $css = file_get_contents($baseUrl . '?debug=true' );
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
    return;
}
header('Content-Type: text/css');
require_once(__DIR__ . '/sourcemap.inc.php');
_css();
?>
.class1 {
    font-size: 10px;
    font-weight: 300;
}
<?php include __DIR__ . '/partial.css.php'; ?>
/*# sourceMappingURL=css.php?map=true */