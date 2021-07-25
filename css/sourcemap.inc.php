<?php
if (!function_exists('_css')) {
    if (isset($_GET['debug']) && $_GET['debug'] == 'true') {
        function _css() {
            $bt = debug_backtrace();
            echo '/*# ' . str_replace(__DIR__, '', $bt[0]['file']) . ':' . $bt[0]['line'] . ' */' . PHP_EOL;
        }
    }
    else {
        function _css() { }
    }
}