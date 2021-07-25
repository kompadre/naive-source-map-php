<?php
define('__DEBUG', true);
require_once __DIR__ . '/map.css.php';
if (isset($_GET['map']) && $_GET['map'] == 'true') {
    renderSourceMap();
    exit();
}
header('Content-Type: text/css');
_map_hint();
?>
<?php /** @lang CSS */ ?>
.class1 {
    font-size: 10px;
    font-weight: 300;
}
<?php include __DIR__ . '/partial.css.php'; ?>
/*# sourceMappingURL=<?= $_SERVER['SCRIPT_NAME'] ?>?map=true */