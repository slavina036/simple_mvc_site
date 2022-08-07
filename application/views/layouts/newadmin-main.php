<?php
use ItForFree\SimpleMVC\Config;

$User = Config::getObject('core.user.class');

?>
<!DOCTYPE html>
<html>
    <?php include('includes/newmain/head.php'); ?>
    <body>
        <?php include('includes/newadmin-main/nav.php'); ?>
        <div class="container">
            <?= $CONTENT_DATA ?>
        </div>
        <?php include('includes/newmain/footer.php'); ?>
    </body>
</html>