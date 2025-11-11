<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Application\Hello;

require __DIR__ . '/../includes/page-top.php';
?>

    <h1>PHP &amp; MySQL</h1>
    <p><?= Hello::message() ?></p>

<?php require __DIR__ . '/../includes/page-bottom.php';
