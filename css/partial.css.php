<?php _css(); ?>
.class2 {
    font-size: 13px;
}
.class3 {
    border-radius: 10px;
}
<?php for($i=1;$i<10;$i++) : ?>
<?php _css(); ?>
.class<?= '' . ($i+3) ?> {
    border-radius: <?= $i ?>px;
}
<?php endfor; ?>