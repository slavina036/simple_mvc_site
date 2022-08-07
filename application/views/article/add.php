<?php
use ItForFree\SimpleMVC\Config;
use application\models\User;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>

<?php // include('includes/admin-articles-nav.php'); ?>
<h1><?= $addArticleTitle ?></h1>

<form id="addArticle" method="post" action="<?= $Url::link("admin/articles/add")?>">
    <ul>
        <li>
            <label for="title">Название</label>
            <input type="text" name="title" id="title" placeholder="Название статьи" required autofocus maxlength="255" value="" />
        </li>
        <li>
            <label for="summary">Краткое содержание</label>
            <textarea name="summary" id="summary" placeholder="Краткое описание статьи" required maxlength="1000" style="height: 5em;"></textarea>
        </li>
        <li>
                <label for="summary">Краткое содержание</label>
                <textarea name="summary" id="summary" placeholder="Краткое описание статьи" required maxlength="1000" style="height: 5em;"></textarea>
              </li>
        <li>
            <label for="content">Содержание</label>
            <textarea name="content" id="content" placeholder="HTML-содержимое статьи" required maxlength="100000" style="height: 30em;"></textarea>
        </li>
        <li>
            <label for="categoryId">Следующая статья</label>
            <select name="nextArticleId" required>
                <option value=""<?= "selected"?> disabled>(Не выбрано)</option>
                <?php foreach ($listArticlesIdTitle as $articleIdTitle) {?>
                  <option value="<?= $articleIdTitle->id?>"><?= htmlspecialchars($articleIdTitle->title)?></option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="categoryId">Категория и подкатегория</label>
            <select name="subcategoryId" required>
              <option value=""<?= "selected"?>>(Не выбрано)</option>
            <?php
            $currentCategoryName = null;
            foreach ( $subcategories as $subcategory ) {
                if($currentCategoryName !== $subcategory->category_name) {
                    if (!is_null($currentCategoryName)) { ?>
                        </optgroup>
                    <?php  }
                    $currentCategoryName = $subcategory->category_name;?>
                    <optgroup label="<?= $subcategory->category_name ?>">
                <?php } ?>
                <option value="<?= $subcategory->id?>"><?= htmlspecialchars($subcategory->name)?></option>
            <?php } ?>
            </optgroup>
            </select>
        </li>
        <li>
            <label for="authors">Авторы</label><br>
            <select name="authors[]" multiple required>
                <option value=""<?= "selected"?> disabled>(Не выбрано)</option>
                <?php
                    $authorsIdList = array_map(
                      fn(User $author): int => $author->id,
                      $viewArticle->authors ?? []
                    );
                    foreach ($users as $user) {?>
                        <option value="<?= $user->id?>"<?= (in_array($user->id, $authorsIdList)) ? " selected" : "" ?>><?= htmlspecialchars($user->login)?></option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="publicationDate">Дата публикации</label>
            <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value=""/>
        </li>
        <li>
            <label for="active">Активность</label>
            <input type="checkbox" name="active" id="active" value="1"/>
        </li>
    </ul>
    <div class="buttons">
        <input type="submit" name="saveNewArticle" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Отменить">
    </div>
</form>