<?php

use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');

?>
<?php
//    echo '<pre>';
//    print_r(count($viewArticles));
//    echo '</pre>';
//    exit;
?>

<ul id="headlines">
    <h1>Всего статей: <?= count($viewArticles)?></h1>
<?php foreach ($viewArticles as $article) { ?>
    <li class='<?= $article->id?>'>
        <h2>
            <span class="pubDate">
                <?= date('j F', $article->publicationDate)?>
            </span>

            <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/articleById&id='
		. $article->id . ">{$article->title}</a>" ) ?></a>

            <?php if (isset($article->categoryId)) { ?>
                <span class="category">
                    Категория:
                    <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleByCategory&id='
                    . $article->categoryId . ">{$viewCategories [$article->categoryId]->name}</a>" ) ?></a>
                </span>
            <?php }
            else { ?>
                <span class="category">
                    <?= "Без категории"?>
                </span>
           <?php } ?>

            <?php if (isset($article->subcategoryId)) { ?>
                <span class="category">
                    Подкатегория:
                    <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleBySubcategory&id='
                    . $article->subcategoryId . ">{$viewSubcategories [$article->subcategoryId]->name}</a>" ) ?></a>
                </span>
            <?php }
            else { ?>
                <span class="category">
                    <?= "Без категории"?>
                </span>
            <?php } ?>

            <span class="category">
                Авторы:
                <br>
                <?php
                    foreach ($article->authors as $key => $author) { ?>
                        <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('article/listArticleByAuthor&id='
                        . $author->id . ">{$author->login}</a>" ) ?></a><br>
                <?php } ?>
            </span>

            <span class="category">
                Уникальных просмотров:
                <a><?= $article->unique_views?></a>
            </span>

            <span class="category">
                Всего просмотров:
                <a><?= $article->all_views?></a>
            </span>

        </h2>

        <p class="summary" data-contentId="<?= $article->id?>"><?= mb_substr(htmlspecialchars($article->content), 0, 50, 'utf8').'...'?></p>
        <img id="loader-identity" src="/images/ajax-loader.gif" alt="gif">

<!--        <ul class="ajax-load">
            <li><a class="ajaxArticleBodyByPost" data-contentId="<?= $article->id?>">Показать продолжение (POST)</a></li>
            <li><a class="ajaxArticleBodyByGet" data-contentId="<?= $article->id?>">Показать продолжение (GET)</a></li>
        </ul>-->

        <ul class="new-ajax-load">
            <li><a class="newAjaxArticleBodyByPost" data-contentId="<?= $article->id?>">Показать продолжение (POST)</a></li>
            <li><a class="newAjaxArticleBodyByGet" data-contentId="<?= $article->id?>" >Показать продолжение (GET)</a></li>
        </ul>

        <a class="showContent" <?= "<a  href=" . \ItForFree\SimpleMVC\Url::link('article/articleById&id='
		. $article->id . ">Показать полностью</a>" ) ?></a>

    </li>

<?php } ?>

</ul>
<p><a href="<?= Url::link('archive/index') ?>">Архив статей</a></p>
<p><a href="<?= Url::link('homepage/index') ?>">Вернуться на главную страницу</a></p>
<?php // include "templates/include/footer.php" ?>