<?php
namespace application\controllers\admin;

use application\models\Article;
use application\models\Category;
use application\models\Subcategory;
use ItForFree\SimpleMVC\Config;

/*
 *   Class-controller Categories
 */

class CategoriesController extends \ItForFree\SimpleMVC\mvc\Controller
{

    public $layoutPath = 'admin-main.php';


    public function indexAction()
    {
        $Category = new Category();

        $CategoryId = $_GET['id'] ?? null;

        if ($CategoryId) { // если указан конктреный пользователь
            $viewCategories = $Category->getById($_GET['id']);
            $this->view->addVar('viewCategories', $viewCategories);
            $this->view->render('category/view-item.php');
        } else { // выводим полный список

            $categories = $Category->getList()['results'];
            $this->view->addVar('categories', $categories);
            $this->view->render('category/index.php');
        }
    }


    /**
     * Выводит на экран форму для создания новой статьи (только для Администратора)
     */
    public function addAction()
    {
        $Url = Config::get('core.url.class');
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewCategory'])) {
                $Category = new Category();
                $newCategory = $Category->loadFromArray($_POST);
                $newCategory->insert();
                $this->redirect($Url::link("admin/categories/index"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/categories/index"));
            }
        }
        else {
            $addCategoryTitle = "Новая категория";
            $this->view->addVar('addCategoryTitle', $addCategoryTitle);
            $this->view->render('category/add.php');
        }
    }


    /**
     * Выводит на экран форму для редактирования категории (только для Администратора)
     */
    public function editAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.url.class');

        if (!empty($_POST)) { // это выполняется нормально.

            if (!empty($_POST['saveChanges'] )) {
                $Category = new Category();
                $newCategory = $Category->loadFromArray($_POST);
                $newCategory->update();
                $this->redirect($Url::link("admin/categories/index"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/categories/index"));
            }
        }
        else {
            $Category = new Category();
            $viewCategory = $Category->getById($id);

            $editCategoryTitle = "Редактирование категории";

            $this->view->addVar('viewCategory', $viewCategory);
            $this->view->addVar('editCategoryTitle', $editCategoryTitle);

            $this->view->render('category/edit.php');
        }
    }


    /**
     * Удаление категории
     */
    public function deleteAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.url.class');

        $Article = new Article();
        $articles = $Article->getList(categoryId: $id)['results'];

        if (count($articles) == 0){
            $Category = new Category();
            $newCategory = $Category->getById($id);
            $newCategory->delete();
            $this->redirect($Url::link("admin/categories/index"));
        } else {
            $this->redirect($Url::link("admin/categories/index"));
        }
    }
}