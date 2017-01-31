<?php
/**
 * Created by PhpStorm.
 * User: Koenv
 * Date: 23-1-2017
 * Time: 23:38
 */

include_once ROOT.'/lib/phpqrcode/qrlib.php';

QRcode::png($this->qr_code, ROOT.'/img/temp.png');
?>

<div class="qr-code">
    <h1>Je code is</h1>
    <img class="qr-image" src="http://<?= BASE ?>/img/temp.png"/>
</div>
