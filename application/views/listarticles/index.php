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
//    exit;
?>

<ul id="headlines">
<?php foreach ($articles as $article) { ?>
    <li class='<?= $article->id?>'>
        <h2>
            <span class="pubDate">
                <?= date('j F', $article->publicationDate)?>
            </span>

            <a  <?= "<a href=" . \ItForFree\SimpleMVC\Url::link('admin/articles/index&id='
		. $article->id . ">{$article->title}</a>" ) ?></a>

            <?php if (isset($article->categoryId)) { ?>
                <span class="category">
                    Категория:
                    <a href=".?action=archive&amp;categoryId=<?= $article->categoryId?>">
                        <?= htmlspecialchars($categories [$article->categoryId]->name)?>
                    </a>
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
                    <a href=".?action=archive&amp;subcategoryId=<?= $article->subcategoryId?>">
                        <?= htmlspecialchars($subcategories [$article->subcategoryId]->name )?>
                    </a>
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
                <a href=".?action=archive&amp;userId=<?= $author->id?>">
                    <?= $author->login?>
                </a><br>
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

        <a href=".?action=viewArticle&amp;articleId=<?= $article->id?>" class="showContent" data-contentId="<?= $article->id?>">Показать полностью</a>

    </li>

<?php } ?>


</ul>
<p><a href="<?= Url::link('archive/index') ?>">Архив статей</a></p>
<?php // include "templates/include/footer.php" ?>

