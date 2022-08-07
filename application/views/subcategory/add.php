<?php
use ItForFree\SimpleMVC\Config;
use application\models\User;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>
<?php
//    echo '<pre>';
//    print_r($categories);
//    echo '</pre>';
//    die();
?>
<?php // include('includes/admin-subcategories-nav.php'); ?>
<h2><?= $addSubcategoryTitle ?></h2>
<form id="addSubcategory" method="post" action="<?= $Url::link("admin/subcategories/add")?>">
    <ul>
        <li>
            <label for="name">Название</label>
            <input type="text" name="name" id="name" placeholder="Название подкатегории" required autofocus maxlength="255" value="">
        </li>
        <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание подкатегории" required maxlength="1000" style="height: 5em;"></textarea>
        </li>
        <li>
            <label for="categoryId">Категория</label>
            <select name="categoryId" required>
            <option value=""<?= "selected"?> disabled>(Не выбрано)</option>
            <?php foreach ($categories as $categoryIdTitle) {?>
                <option value="<?= $categoryIdTitle->id?>"><?= htmlspecialchars($categoryIdTitle->name)?></option>
            <?php } ?>
            </option>
        </select>
        </li>
    </ul>
    <div class="buttons">
        <input type="submit" name="saveNewSubcategory" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Отменить">
    </div>
</form>
