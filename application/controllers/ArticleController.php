<?php

namespace application\controllers;

use ItForFree\SimpleMVC\Config;
use application\models\Article;
use application\models\Category;
use application\models\Subcategory;
use application\models\ExampleUser;

/**
 * Контроллер для страницы вывода статей
 */
class ArticleController extends \ItForFree\SimpleMVC\mvc\Controller
{
    /**
     * @var string Название страницы
     */
    public $pageTitle = "Просмотр статей";

    /**
     * @var string Пусть к файлу макета
     */
    public $layoutPath = 'main.php';

    /**
     * Выводит на экран страницу просмотра одной статьи
     */
    public function articleByIdAction()
    {
        $Article = new Article();
        $Category = new Category();
        $Subcategory = new Subcategory();
        $User = new ExampleUser();

        $viewArticles = $Article->getById($_GET['id']);
        $viewCategories = $Category->getById($viewArticles->categoryId);
        $viewSubcategories = $Subcategory->getById($viewArticles->subcategoryId);
        $viewnextArticle = $Article->getById($viewArticles->nextArticleId);
        $viewpreviousArticle = $Article->getById($viewArticles->previousArticleId);

        $this->view->addVar('viewArticles', $viewArticles);
        $this->view->addVar('viewCategories', $viewCategories);
        $this->view->addVar('viewSubcategories', $viewSubcategories);
        $this->view->addVar('viewnextArticle', $viewnextArticle);
        $this->view->addVar('viewpreviousArticle', $viewpreviousArticle);
        $this->view->render('public/viewArticleById.php');
    }


    /**
     * Выводит на экран страницу со статьями по категориям
     */
    public function listArticleByCategoryAction()
    {
        $Article = new Article();
        $Category = new Category();
        $Subcategory = new Subcategory();
        $User = new ExampleUser();
        $data = array();

        $viewArticles = $Article->getList(categoryId: $_GET['id'])['results'];

        $data = $Category->getList()['results'];
        $viewCategories = array();
        foreach ( $data as $category ) {
            $viewCategories[$category->id] = $category;
        };

        $data = $Subcategory->getList()['results'];
        $viewSubcategories = array();
        foreach ( $data as $subcategory ) {
            $viewSubcategories[$subcategory->id] = $subcategory;
        };

        $data = $User->getList()['results'];
        $viewUsers = array();
        foreach ( $data as $user ) {
            $viewUsers[$user->id] = $user;
        };

        $this->view->addVar('viewArticles', $viewArticles);
        $this->view->addVar('viewCategories', $viewCategories);
        $this->view->addVar('viewSubcategories', $viewSubcategories);
        $this->view->addVar('viewUsers', $viewUsers);
        $this->view->render('public/viewArticleList.php');
    }


    /**
     * Выводит на экран страницу со статьями по подкатегориям
     */
    public function listArticleBySubcategoryAction()
    {
        $Article = new Article();
        $Category = new Category();
        $Subcategory = new Subcategory();
        $User = new ExampleUser();
        $data = array();

        $viewArticles = $Article->getList(subcategoryId: $_GET['id'])['results'];

        $data = $Category->getList()['results'];
        $viewCategories = array();
        foreach ( $data as $category ) {
            $viewCategories[$category->id] = $category;
        };

        $data = $Subcategory->getList()['results'];
        $viewSubcategories = array();
        foreach ( $data as $subcategory ) {
            $viewSubcategories[$subcategory->id] = $subcategory;
        };

        $data = $User->getList()['results'];
        $viewUsers = array();
        foreach ( $data as $user ) {
            $viewUsers[$user->id] = $user;
        };

        $this->view->addVar('viewArticles', $viewArticles);
        $this->view->addVar('viewCategories', $viewCategories);
        $this->view->addVar('viewSubcategories', $viewSubcategories);
        $this->view->addVar('viewUsers', $viewUsers);
        $this->view->render('public/viewArticleList.php');
    }


    /**
     * Выводит на экран страницу со статьями по авторам
     */
    public function listArticleByAuthorAction()
    {
        $Article = new Article();
        $Category = new Category();
        $Subcategory = new Subcategory();
        $User = new ExampleUser();
        $data = array();

        $viewArticles = $Article->getList(userId: $_GET['id'])['results'];

        $data = $Category->getList()['results'];
        $viewCategories = array();
        foreach ( $data as $category ) {
            $viewCategories[$category->id] = $category;
        };

        $data = $Subcategory->getList()['results'];
        $viewSubcategories = array();
        foreach ( $data as $subcategory ) {
            $viewSubcategories[$subcategory->id] = $subcategory;
        };

        $data = $User->getList()['results'];
        $viewUsers = array();
        foreach ( $data as $user ) {
            $viewUsers[$user->id] = $user;
        };

        $this->view->addVar('viewArticles', $viewArticles);
        $this->view->addVar('viewCategories', $viewCategories);
        $this->view->addVar('viewSubcategories', $viewSubcategories);
        $this->view->addVar('viewUsers', $viewUsers);
        $this->view->render('public/viewArticleList.php');
    }


}
