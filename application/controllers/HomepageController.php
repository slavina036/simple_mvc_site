<?php

namespace application\controllers;

use ItForFree\SimpleMVC\Config;
use application\models\Article;
use application\models\Category;
use application\models\Subcategory;
use application\models\ExampleUser;

/**
 * Контроллер для домашней страницы
 */
class HomepageController extends \ItForFree\SimpleMVC\mvc\Controller
{
    /**
     * @var string Название страницы
     */
    public $homepageTitle = "Главная";

    /**
     * @var string Пусть к файлу макета
     */
    public $layoutPath = 'main.php';

    /**
     * Выводит на экран страницу "Домашняя страница"
     */
    public function indexAction()
    {
        $Article = new Article();
        $Category = new Category();
        $Subcategory = new Subcategory();
        $User = new ExampleUser();
        $data = array();

        $articles = $Article->getList(numRows: 5)['results'];

        $data = $Category->getList()['results'];
        $categories = array();
        foreach ( $data as $category ) {
            $categories[$category->id] = $category;
        };

        $data = $Subcategory->getList()['results'];
        $subcategories = array();
        foreach ( $data as $subcategory ) {
            $subcategories[$subcategory->id] = $subcategory;
        };

        $data = $User->getList()['results'];
        $users = array();
        foreach ( $data as $user ) {
            $users[$user->id] = $user;
        };

        $this->view->addVar('articles', $articles);
        $this->view->addVar('categories', $categories);
        $this->view->addVar('subcategories', $subcategories);
        $this->view->addVar('users', $users);

        $this->view->addVar('homepageTitle', $this->homepageTitle); // передаём переменную по view

        $this->view->render('homepage/index.php');


    }
}
