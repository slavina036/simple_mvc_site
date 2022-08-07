
<?php
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;

$User = Config::getObject('core.user.class');


//vpre($User->explainAccess("homepage/index"));

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Меню оформленное с помощью  twitter bootstrap -->
    <span class="navbar-toggler-icon"></span>
 </button>
 <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
        <?php  if ($User->isAllowed("admin/adminusers/index")): ?>
        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("admin/articles/index")?>"> Статьи </a>
        </li>
        <?php endif; ?>
        <?php  if ($User->isAllowed("admin/adminusers/index")): ?>
        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("admin/categories/index")?>"> Категории </a>
        </li>
        <?php endif; ?>
        <?php  if ($User->isAllowed("admin/adminusers/index")): ?>
        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("admin/subcategories/index")?>"> Подкатегории </a>
        </li>
        <?php endif; ?>
        <?php  if ($User->isAllowed("admin/adminusers/index")): ?>
        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("admin/adminusers/index") ?>"> Пользователи </a>
        </li>
        <?php endif; ?>

        <?php //  if ($User->isAllowed("admin/notes/index")): ?>
<!--        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("admin/notes/index") ?>"> Заметки </a>
        </li>-->
        <?php // endif; ?>

        <?php  if ($User->isAllowed("login/logout")): ?>
        <li class="nav-item ">
            <a class="nav-link" href="<?= Url::link("login/logout") ?>">Выход (<?= $User->userName ?>)</a>
        </li>
        <?php endif; ?>

    </ul>
   </div>
</nav>
