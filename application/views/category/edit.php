<?php
use ItForFree\SimpleMVC\Config;
use application\models\User;
use ItForFree\SimpleMVC\Url;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>

<?php // include('includes/admin-articles-nav.php'); ?>
<h1><?= $editCategoryTitle ?></h1>

<form id="editCategory" method="post" action="<?= $Url::link("admin/categories/edit&id=" . $_GET['id'])?>">
    <ul>
        <li>
            <label for="title">Название</label>
            <input type="text" name="name" id="name" placeholder="Название категории" required autofocus maxlength="255" value="<?= htmlspecialchars($viewCategory->name)?>"/>
        </li>
        <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание категории" required maxlength="1000" style="height: 5em;"><?= htmlspecialchars($viewCategory->description)?></textarea>
        </li>
    </ul>
    <div class="buttons">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
        <input type="submit" name="saveChanges" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Назад">
    </div>
</form>
<a href="<?= Url::link("admin/categories/delete&id=" . $viewCategory->id) ?>" onclick="return confirm('Удалить эту категорию?')">Удалить категорию</a>