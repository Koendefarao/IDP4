<?php // STandaard html waar content in wordt geplakt ?>
<html>
<head>
    <title>Sportschool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="http://informatica-cals.nl/edmitriev/js/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/SchoolProjectVanKoen/css/main.css"/>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php //Chekc of gebruiker is ingelogt om login of logout te laten zien ?>
                <li><a href="<?= $this->link(array('action' => 'index', 'controller' => 'main')) ?>">Home</a></li>
                <?php if(!isset($this->customer['first_name'])) {?>
                    <li><a href="<?= $this->link(array('action' => 'login', 'controller' => 'main')) ?>">Login/Register</a></li>
                <?php } else { ?>
                    <li><a href="<?= $this->link(array('action' => 'qrcode', 'controller' => 'main')) ?>">Hello <?= $this->customer['first_name'] . ' ' . $this->customer['last_name'] ?></a></li>
                    <li><a href="<?= $this->link(array('action' => 'logout', 'controller' => 'main')) ?>">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="main-content">
    <div class="container">
        <?= $this->fetch('notification') ?>
    </div>
    <div class="container content">
        <?php // haalt php die hiertussen moet woren latenzien. Notification is onderdeel
        //van Notigications Component ?>

        <?= $this->fetch('content') ?>
    </div>
</div>
</body>
</html>