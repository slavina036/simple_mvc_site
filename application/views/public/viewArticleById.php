<?php
use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');
?>
<?php
//    echo '<pre>';
//    print_r($articles);
//    echo '</pre>';
//    die();
?>
<?php // include('includes/admin-articles-nav.php'); ?>

<h1 style="width: 75%;"><?= $viewArticles->title ?></h1>
<div style="width: 75%; font-style: italic;"><?= $viewArticles->summary ?></div>
<div style="width: 75%;"><?= $viewArticles->content ?></div>

<p class="pubDate">Опубликовано <?= date('j M Y', $viewArticles->publicationDate)?>

    <?php if(isset ($viewArticles->categoryId)) { ?>
        в категории "
        <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleByCategory&id='
                    . $viewArticles->categoryId . ">{$viewCategories->name}</a>" ) ?></a>
        <?php }
        else {
        echo "Без категории";
        } ?></a>"

    <?php if(isset ($viewArticles->subcategoryId)) { ?>
        подкатегории "
        <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleBySubcategory&id='
                    . $viewArticles->subcategoryId . ">{$viewSubcategories->name}</a>" ) ?></a>
        <?php }
        else {
        echo "Без подкатегории";
        } ?></a>
</p>
<p>
    Авторы:<br>
    <?php if (isset ($viewArticles->authors)) {
            foreach ($viewArticles->authors as $key => $author) {?>
    <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleByAuthor&id='
                    . $author->id . ">{$author->login}</a>" ) ?></a><br><?php
            }
        } else {
        echo "Авторы не указаны";
        }?></p>

<p id="move-article">
    <?php if (isset($viewpreviousArticle->id)) { ?>
        <a id="previous" <?= "<a href=" . Url::link('article/articleById&id=' . $viewpreviousArticle->id . "> << Предыдущая статья</a>" ) ?><a/>
    <?php } ?>
    <?php if (isset($viewnextArticle->id)) { ?>
        <a id="next" <?= "<a href=" . Url::link('article/articleById&id=' . $viewnextArticle->id . "> Следующая статья >></a>" ) ?><a/>
    <?php } ?>
</p>

<p><a href="<?= Url::link('homepage/index') ?>">Вернуться на главную страницу</a></p>
<p><a href="<?= Url::link('archive/index') ?>">Архив статей</a></p>