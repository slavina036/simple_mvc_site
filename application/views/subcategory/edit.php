<?php
use ItForFree\SimpleMVC\Config;
use application\models\User;
use ItForFree\SimpleMVC\Url;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>

<?php // include('includes/admin-articles-nav.php'); ?>
<h1><?= $editSubcategoryTitle ?></h1>

<form id="editSubcategory" method="post" action="<?= $Url::link("admin/subcategories/edit&id=" . $_GET['id'])?>">
    <ul>
        <li>
            <label for="name">Название</label>
            <input type="text" name="name" id="name" placeholder="Название подкатегории" required autofocus maxlength="255" value="<?= htmlspecialchars($viewSubcategory->name)?>">
        </li>
        <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание подкатегории" required maxlength="1000" style="height: 5em;"><?= htmlspecialchars($viewSubcategory->description)?></textarea>
        </li>
        <li>
            <label for="categoryId">Категория</label>
            <select name="categoryId" required>
                <option value=""<?= !$viewSubcategory->categoryId ? "selected" : ""?> disabled>(Не выбрано)</option>
                <?php foreach ($categories as $categoryIdTitle) {?>
                    <option value="<?= $categoryIdTitle->id?>"<?= ( $categoryIdTitle->id == $viewSubcategory->categoryId ) ? " selected" : ""?> ><?= htmlspecialchars($categoryIdTitle->name)?></option>
                <?php } ?>
                </option>
            </select>
        </li>
    </ul>
    <div class="buttons">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
        <input type="submit" name="saveChanges" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Назад">
    </div>
</form>
<a href="<?= Url::link("admin/subcategories/delete&id=" . $viewSubcategory->id) ?>" onclick="return confirm('Удалить эту подкатегорию?')">Удалить подкатегорию</a>