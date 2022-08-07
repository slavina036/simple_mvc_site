<?php
namespace application\controllers;

use application\models\Article;

/**
 * Можно использовать для обработки ajax-запросов.
 */
class AjaxController extends \ItForFree\SimpleMVC\mvc\Controller
{

    /**
     * Подгрузка "лайков" статей или товаров
     */
    public function likeAction()
    {
       echo 'привет!';
    }


    public function showSummaryAction()
    {
        if (isset($_GET['articleId'])) {
            $Article = new Article();
            $article = $Article->getById((int)$_GET['articleId']);
            $article->content .= " Контент загружен без перезагрузки страницы с помощью ajax-запроса type: 'GET'";
            echo $article->content;
        }

        if (isset ($_POST['articleId'])) {
            $Article = new Article();
            $article = $Article->getById((int)$_POST['articleId']);
//                echo '<pre>';
//                print_r($article->content);
//                echo '</pre>';
//                die();
            $article->content .=  " Контент загружен без перезагрузки страницы с помощью ajax-запроса type: 'POST'";
            echo json_encode($article);
        }
//        if (isset ($_POST['articleId'])) {
//            $article = Article::getById((int)$_POST['articleId']);
//            $article->content .=  " Контент загружен без перезагрузки страницы с помощью ajax-запроса type: 'POST'";
//            echo json_encode($article);
//        }
    }
}

