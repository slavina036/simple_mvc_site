<?php
use ItForFree\SimpleMVC\Config;
use application\models\ExampleUser;
use ItForFree\SimpleMVC\Url;

$Url = Config::getObject('core.url.class');
$User = Config::getObject('core.user.class');
?>

<?php
//    echo '<pre>';
//    print_r($users);
//    echo '</pre>';
//    die();
?>

<?php // include('includes/admin-articles-nav.php'); ?>
<h1><?= $editArticleTitle ?></h1>

<form id="editArticle" method="post" action="<?= $Url::link("admin/articles/edit&id=" . $_GET['id'])?>">
    <ul>
        <li>
            <label for="title">Название</label>
            <input type="text" name="title" id="title" placeholder="Название статьи" required autofocus maxlength="255" value="<?= htmlspecialchars( $viewArticle->title )?>" />
        </li>
        <li>
            <label for="summary">Краткое содержание</label>
            <textarea name="summary" id="summary" placeholder="Краткое описание статьи" required maxlength="1000" style="height: 5em;"><?= htmlspecialchars( $viewArticle->summary )?></textarea>
        </li>
        <li>
                <label for="summary">Краткое содержание</label>
                <textarea name="summary" id="summary" placeholder="Краткое описание статьи" required maxlength="1000" style="height: 5em;"><?= htmlspecialchars( $viewArticle->summary )?></textarea>
              </li>
        <li>
            <label for="content">Содержание</label>
            <textarea name="content" id="content" placeholder="HTML-содержимое статьи" required maxlength="100000" style="height: 30em;"><?= htmlspecialchars( $viewArticle->content )?></textarea>
        </li>
        <li>
            <label for="categoryId">Следующая статья</label>
            <select name="nextArticleId" required>
                <option value=""<?= !$viewArticle->nextArticleId ? " selected" : "" ?> disabled>(Не выбрано)</option>
                <?php foreach ($listArticlesIdTitle as $articleIdTitle) {?>
                  <option value="<?= $articleIdTitle->id?>"<?= ( $articleIdTitle->id == $viewArticle->nextArticleId ) ? " selected" : ""?>><?= htmlspecialchars($articleIdTitle->title)?></option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="categoryId">Категория и подкатегория</label>
            <select name="subcategoryId" required>
              <option value=""<?= !$viewArticle->subcategoryId ? " selected" : ""?>>(Не выбрано)</option>
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
                <option value="<?= $subcategory->id?>"<?= ($subcategory->id == $viewArticle->subcategoryId ) ? " selected" : ""?>><?= htmlspecialchars($subcategory->name)?></option>
            <?php } ?>
            </optgroup>
            </select>
        </li>
        <li>
            <label for="authors">Авторы</label><br>
            <select name="authors[]" multiple required>
                <option value=""<?= !$viewArticle->authors ? " selected" : "" ?> disabled>(Не выбрано)</option>
                <?php
                    $authorsIdList = array_map(
                      fn(ExampleUser $author): int => $author->id,
                      $viewArticle->authors ?? []
                    );
                    foreach ($users as $user) {?>
                        <option value="<?= $user->id?>"<?= (in_array($user->id, $authorsIdList)) ? " selected" : "" ?>><?= htmlspecialchars($user->login)?></option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="publicationDate">Дата публикации</label>
            <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?= $viewArticle->publicationDate ? date( "Y-m-d", $viewArticle->publicationDate ) : "" ?>" />
        </li>
        <li>
            <label for="active">Активность</label>
            <input type="checkbox" name="active" id="active" value="1" <?= $viewArticle->active ? 'checked' : '' ?> />
        </li>
    </ul>
    <div class="buttons">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
        <input type="submit" name="saveChanges" value="Сохранить">
        <input type="submit" formnovalidate name="cancel" value="Назад">
    </div>
</form>
<a href="<?= Url::link("admin/articles/delete&id=" . $viewArticle->id) ?>" onclick="return confirm('Удалить эту статью?')">Удалить статью</a>