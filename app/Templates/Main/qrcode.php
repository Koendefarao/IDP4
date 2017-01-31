<?php
/**
 * Created by PhpStorm.
 * User: Koenv
 * Date: 23-1-2017
 * Time: 23:38
 */

include_once ROOT . '/lib/phpqrcode/qrlib.php';

QRcode::png($this->qr_code, ROOT . '/img/temp.png');
?>

    <div class="qr-code">
        <h1>Je code is</h1>
        <img class="qr-image" src="http://<?= BASE ?>/img/temp.png"/>
    </div>

    <br>
    <br>
    <br>
<?php
if (count($this->devices) > 0) { ?>
    <h1>Geschiedenis</h1>
    <div class="row">
        <?php
        for ($i = count($this->devices) -1; $i >=0; $i--) {
        $device = $this->devices[$i]?>
            <div class="col-md-3 col-sm-6">
                <div class="history-record">
                    <img src="<?= $device['image'] ?>"/>
                    <h2><?= $device['name'] ?></h2>
                    <h5>Used at: <?= $device['created'] ?></h5>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>