<?php
use ItForFree\SimpleMVC\Config;
use application\models\User;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>

<?php // include('includes/admin-articles-nav.php'); ?>
<h1><?= $addCategoryTitle ?></h1>

<form id="addCategory" method="post" action="<?= $Url::link("admin/categories/add")?>">
    <ul>
        <li>
            <label for="name">Название</label>
            <input type="text" name="name" id="name" placeholder="Название категории" required autofocus maxlength="255" value=""/>
        </li>
        <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание категории" required maxlength="1000" style="height: 5em;"></textarea>
        </li>
    </ul>
    <div class="buttons">
        <input type="submit" name="saveNewCategory" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Отменить">
    </div>
</form>