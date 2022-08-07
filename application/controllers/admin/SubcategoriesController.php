<?php
namespace application\controllers\admin;

use application\models\Article;
use application\models\Category;
use application\models\Subcategory;
use ItForFree\SimpleMVC\Config;

/*
 *   Class-controller Subcategories
 */

class SubcategoriesController extends \ItForFree\SimpleMVC\mvc\Controller
{

    public $layoutPath = 'admin-main.php';


    public function indexAction()
    {
        $Subcategory = new Subcategory();

        $SubcategoryId = $_GET['id'] ?? null;

        if ($SubcategoryId) { // если указан конктреный пользователь
            $viewSubcategories = $Subcategory->getById($_GET['id']);
            $this->view->addVar('viewSubcategories', $viewSubcategories);
            $this->view->render('subcategory/view-item.php');
        } else { // выводим полный список

            $subcategories = $Subcategory->getList()['results'];
            $this->view->addVar('subcategories', $subcategories);
            $this->view->render('subcategory/index.php');
        }
    }

    /**
     * Выводит на экран форму для создания новой подкатегории
     */
    public function addAction()
    {
        $Url = Config::get('core.url.class');
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewSubcategory'])) {
                $Subcategory = new Subcategory();
                $newSubcategories = $Subcategory->loadFromArray($_POST);
                $newSubcategories->insert();
                $this->redirect($Url::link("admin/subcategories/index"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/subcategories/index"));
            }
        }
        else {
            $Category = new Category();
//            $data = array();
            $categories = $Category->getList()['results'];
//            $categories = array();
//            foreach ( $data as $category ) {
//                $categories[$category->id] = $category;
            $addSubcategoryTitle = "Новая подкатегории";
            $this->view->addVar('addSubcategoryTitle', $addSubcategoryTitle);
            $this->view->addVar('categories', $categories);
            $this->view->render('subcategory/add.php');
            };
    }

    /**
     * Выводит на экран форму для редактирования статьи (только для Администратора)
     */
    public function editAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.url.class');

        if (!empty($_POST)) { // это выполняется нормально.

            if (!empty($_POST['saveChanges'] )) {
                $Subcategory = new Subcategory();
                $newSubcategory = $Subcategory->loadFromArray($_POST);
                $newSubcategory->update();
                $this->redirect($Url::link("admin/subcategories/index"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/subcategories/index"));
            }
        }
        else {
            $Category = new Category();
            $categories = $Category->getList()['results'];
//            print_r($categories);
//            die();
//            foreach ( $categories as $category ) {
//                $categories[$category->id] = $category;
//            };

            $Subcategory = new Subcategory();
            $viewSubcategory = $Subcategory->getById($id);

            $editSubcategoryTitle = "Редактирование подкатегории";

            $this->view->addVar('editSubcategoryTitle', $editSubcategoryTitle);
            $this->view->addVar('viewSubcategory', $viewSubcategory);
            $this->view->addVar('categories', $categories);

            $this->view->render('subcategory/edit.php');
        }

    }

    /**
     * Удаление подкатегории
     */
    public function deleteAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.url.class');

        $Article = new Article();
        $articles = $Article->getList(subcategoryId: $id)['results'];
        
        if (count($articles) == 0){
            $Subcategory = new Subcategory();
            $newSubcategory = $Subcategory->getById($id);
            $newSubcategory->delete();
            $this->redirect($Url::link("admin/subcategories/index"));
        } else {
            $this->redirect($Url::link("admin/subcategories/index"));
        }
    }


}