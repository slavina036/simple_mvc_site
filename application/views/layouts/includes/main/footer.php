<?php use ItForFree\SimpleAsset\SimpleAssetManager;

use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');

SimpleAssetManager::printJS();
?>
        <div id="footer">
            SimpleMVC -- учебный проект &copy; 2017.
            <?php if ($User->isAllowed("admin/adminusers/index")) { ?>
                <a  href="<?= Url::link("admin/articles/index") ?>">Site admin</a>
            <?php } else { ?>
                <a href="<?= Url::link("login/login") ?>">Site admin</a>
            <?php } ?>
        </div>
    </body>
</html>
