<?php
use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');
?>
<?php
//    echo '<pre>';
//    print_r($message);
//    echo '</pre>';
?>

<h1>Список статей</h1>
<p>Всего статей: <?= count($articles)?></p>
<p><a href="<?= Url::link("admin/articles/add") ?>">Добавить новую статью</a></p>
<?php if ( isset( $message['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $message['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $message['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $message['statusMessage'] ?></div>
    <?php } ?>
<table>
    <tr>
        <th scope="col">Дата публикации</th>
        <th scope="col">Название статьи</th>
        <th scope="col">Категория</th>
        <th scope="col">Подкатегория</th>
        <th scope="col">Авторы</th>
        <!--<th scope="col">Уникальные просмотры</th>-->
        <!--<th scope="col">Все просмотры</th>-->
        <th scope="col">Активность</th>

    </tr>
    <?php foreach($articles as $article): ?>
        <tr onclick="location='<?= \ItForFree\SimpleMVC\Url::link('admin/articles/edit&id=' . $article->id)?>'">

        <td><?= date('j M Y', $article->publicationDate)?></td>

        <td><?= $article->title?></td>

        <td> <?php if(isset ($article->categoryId)) {
                    echo $categories[$article->categoryId]->name;
                }
                else {
                echo "Без категории";
                } ?></td>

        <td> <?php if(isset ($article->subcategoryId)) {
                    echo $subcategories[$article->subcategoryId]->name;
                }
                else {
                echo "Без подкатегории";
                } ?></td>

        <td><?php if (isset ($article->authors)) {
                      foreach ($article->authors as $key => $author) {
                          echo $author->login?><br><?php
                      }
                  } ?></td>

        <!--<td> <?= $article->unique_views ?> </td>-->
        <!--<td> <?= $article->all_views ?> </td>-->
        <td><input type="checkbox" name="active" id="active" value="1" <?= $article->active ? 'checked' : '' ?> disabled></td>
    </tr>
    <?php endforeach; ?>

</table>