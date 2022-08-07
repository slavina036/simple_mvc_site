<?php
namespace application\controllers\admin;

use ItForFree\SimpleMVC\Config;
use application\models\Article;
use application\models\Category;
use application\models\Subcategory;
use application\models\ExampleUser;

/*
 *   Class-controller articles
 */

class ArticlesController extends \ItForFree\SimpleMVC\mvc\Controller
{

    public $layoutPath = 'admin-main.php';


    public function indexAction()
    {
        $articleId = $_GET['id'] ?? null;

        if ($articleId) { // если указана конкретная статья

            $Article = new Article();
            $Category = new Category();
            $Subcategory = new Subcategory();
            $User = new ExampleUser();

            $viewArticles = $Article->getById($_GET['id']);
            $viewCategories = $Category->getById($viewArticles->categoryId);
            $viewSubcategories = $Subcategory->getById($viewArticles->subcategoryId);
            $viewnextArticle = $Article->getById($viewArticles->nextArticleId);

            $this->view->addVar('viewArticles', $viewArticles);
            $this->view->addVar('viewCategories', $viewCategories);
            $this->view->addVar('viewSubcategories', $viewSubcategories);
            $this->view->addVar('viewnextArticle', $viewnextArticle);
            $this->view->render('article/view-item.php');

        } else { // выводим полный список

            $Article = new Article();
            $Category = new Category();
            $Subcategory = new Subcategory();
            $User = new ExampleUser();
            $data = array();

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

            $articles = $Article->getList()['results'];

            if (isset($_GET['status'])) { // вывод сообщения (если есть)
                if ($_GET['status'] == "changesSaved") {
                    $message['statusMessage'] = "Ваши изменения сохранены";
                    $this->view->addVar('message', $message);
                }
                if ($_GET['status'] == "articleDeleted")  {
                    $message['statusMessage'] = "Статья удалена";
                    $this->view->addVar('message', $message);
                }
                if ($_GET['status'] == "articleCancel")  {
                    $message['statusMessage'] = "Изменения отменены";
                    $this->view->addVar('message', $message);
                }
            }

            $this->view->addVar('categories', $categories);
            $this->view->addVar('subcategories', $subcategories);
            $this->view->addVar('users', $users);
            $this->view->addVar('articles', $articles);
            $this->view->render('article/index.php');
        }
    }

    /**
     * Выводит на экран форму для создания новой статьи (только для Администратора)
     */
    public function addAction()
    {
        $Url = Config::get('core.url.class');
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewArticle'])) {
                $Article = new Article();

            if (isset($_POST['subcategoryId'])) {
              $Subcategory = new Subcategory();
              $subcategory = $Subcategory->getById(id: $_POST['subcategoryId']);
              $categoryId = $subcategory->categoryId;
              $_POST['categoryId'] = $categoryId;
          }

                $newArticle = $Article->loadFromArray($_POST);
                $newArticle->insert();
                $this->redirect($Url::link("admin/articles/index&status=articleSave"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/articles/index&status=articleCancel"));
            }
        }
        else {
            $Article = new Article();
            $Subcategory = new Subcategory();
            $User = new ExampleUser();
            $data = array();

            $data = $Article->getListArticlesIdTitle();
            $listArticlesIdTitle = array();
            foreach ( $data as $article ) {
                $listArticlesIdTitle[$article->id] = $article;
            };

            $data = $Subcategory->getList(order: "sc.categoryId")['results'];
            $subcategories = array();
            foreach ( $data as $subcategory ) {
                $subcategories[$subcategory->id] = $subcategory;
            };

            $data = $User->getList()['results'];
            $users = array();
            foreach ( $data as $user ) {
                $users[$user->id] = $user;
            };

            $addArticleTitle = "Новая сатья";
            $this->view->addVar('listArticlesIdTitle', $listArticlesIdTitle);
            $this->view->addVar('subcategories', $subcategories);
            $this->view->addVar('users', $users);
            $this->view->addVar('addArticleTitle', $addArticleTitle);
            $this->view->render('article/add.php');
        }
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
                $Article = new Article();
                $Subcategory = new Subcategory();
                if (isset($_POST['subcategoryId'])) {
                    $subcategory = $Subcategory->getById($_POST['subcategoryId']);
                    $categoryId = $subcategory->categoryId;
                    $_POST['categoryId'] = $categoryId;
                }

                $newArticle = $Article->loadFromArray($_POST);
                $newArticle->update();
                $this->redirect($Url::link("admin/articles/index&status=changesSaved"));
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/articles/index&status=articleCancel"));
            }
        }
        else {
            $Article = new Article();
            $Category = new Category();
            $Subcategory = new Subcategory();
            $User = new ExampleUser();
            $data = array();

            $viewArticle = $Article->getById($id);

            $data = $Article->getListArticlesIdTitle();
            $articles = array();
            foreach ( $data as $article ) {
                $articles[$article->id] = $article;
            };

            $listArticlesIdTitle = $Article->getListArticlesIdTitle();

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

            $editArticleTitle = "Редактирование статьи";

            $this->view->addVar('listArticlesIdTitle', $listArticlesIdTitle);
            $this->view->addVar('viewArticle', $viewArticle);
            $this->view->addVar('categories', $categories);
            $this->view->addVar('subcategories', $subcategories);
            $this->view->addVar('users', $users);
            $this->view->addVar('editArticleTitle', $editArticleTitle);

            $this->view->render('article/edit.php');
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
        $newArticle = $Article->getById($id);
        $newArticle->delete();
        $this->redirect($Url::link("admin/articles/index&status=articleDeleted"));
    }
}