
<?php
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;

$User = Config::getObject('core.user.class');

//vpre($User->explainAccess("homepage/index"));

?>
<div id="adminHeader">
    <h2>Widget News Admin</h2>
    <p>Вы вошли как <b><?php echo htmlspecialchars($User->userName) ?></b>.
        <a href="<?= Url::link("admin/articles/index")?>">Статьи</a>
        <a href="<?= Url::link("admin/categories/index")?>">Категории</a>
        <a href="<?= Url::link("admin/subcategories/index")?>">Подкатегории</a>
        <?php  if ($User->isAllowed("admin/adminusers/index")): ?>
            <a href="<?= Url::link("admin/adminusers/index") ?>"> Пользователи </a>
        <?php endif; ?>
        <a href="<?= Url::link("login/logout")?>">Выйти</a>
    </p>
</div>