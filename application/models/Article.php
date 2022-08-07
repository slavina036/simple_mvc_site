<?php

namespace application\models;

use PDO;

/**
 * Класс для обработки статей
 */

class Article extends \ItForFree\SimpleMVC\mvc\Model
{
    // Свойства
    /**
    * @var int ID статей из базы данны
    */
    public $id = null;

    /**
    * @var int Дата первой публикации статьи
    */
    public $publicationDate = null;

    /**
    * @var string Полное название статьи
    */
    public $title = null;

     /**
    * @var int ID категории статьи
    */
    public $categoryId = null;

     /**
    * @var int ID категории статьи
    */
    public $subcategoryId = null;

    /**
    * @var string Краткое описание статьи
    */

    public $summary = null;

    /**
    * @var string HTML содержание статьи
    */
    public $content = null;

    /**
    * Список авторов статьи
    */
    public array $authors = [];

    /**
    * Количество уникальных просмотров статьи
    */
    public $unique_views = null;

    /**
    * Общее количество просмотров статьи
    */
    public $all_views = null;

    /**
    *Активность
    */
    public ?int $active = null;

    /**
    *Следующая сатья
    */
    public ?int $nextArticleId = null;

    /**
    *Предыдущая сатья
    */
    public ?int $previousArticleId = null;


    /**
    * Вставляем текущий объек Article в базу данных, устанавливаем его ID.
    */
    public function insert()
    {


        $sql = "INSERT INTO articles ( publicationDate, categoryId, subcategoryId, "
                . "title, summary, content, active, nextArticleId ) "
                . "VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, "
                . ":subcategoryId, :title, :summary, :content, :active, :nextArticleId )";
        $st = $this->pdo->prepare ($sql);

        $st->bindValue( ":publicationDate", (new \DateTime($this->publicationDate))->getTimestamp(), PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
        $st->bindValue( ":nextArticleId", $this->nextArticleId, PDO::PARAM_INT );

        $st->execute();
        $this->id = $this->pdo->lastInsertId();

        foreach ($this->authors as $key => $author) {
            $sql = "INSERT INTO users_articles (userId, articleId) "
                    . "VALUES (:userId, :articleId)";
            $st = $this->pdo->prepare ($sql);
            $st->bindValue( ":userId", $author, PDO::PARAM_INT );
            $st->bindValue( ":articleId", $this->id, PDO::PARAM_INT );
            $st->execute();
        }
    }

    public function getList(int $numRows = 1000000, $categoryId = null,
                                $order = null, $active = null,
                                $subcategoryId = null, $pageCurrent = 1,
                                $userId = null, bool $countViews = true)
    {
        if (empty($order)) {
            $order = "publicationDate DESC";
        }
        $offset = ($pageCurrent - 1) * $numRows;
        $offset .= ", ";

        $withPart = $countViews ? 'WITH table_all_views as (select vc.articleId, SUM(vc.views) '
           . ' as all_views from view_counter vc group by vc.articleId),'
           . ' table_unique_views as (select vc.articleId, COUNT(*) '
           . ' as unique_views from view_counter vc group by vc.articleId)' : '';

        $fromPart = "FROM articles";

        $clauseJoin = $countViews ? 'LEFT JOIN table_unique_views ON table_unique_views.articleId = articles.id '
            .'LEFT JOIN table_all_views ON table_all_views.articleId = articles.id ' : '';

        $clause = '';
        $clauses = [];

        if (!empty($userId)) {
            $clause .= " JOIN users_articles ON users_articles.articleId = articles.id ";
            $clauses[] = 'users_articles.userId = :userId';
        }

        if (!empty($categoryId)) {
            $clauses[] = 'categoryId = :categoryId';
        }
        if (!empty($active)) {
            $clauses[] = 'active= :active';
        }
        if (!empty($subcategoryId)) {
            $clauses[] = 'subcategoryId = :subcategoryId';
        }

        $conditions = implode(' AND ', $clauses);

        if (!empty($conditions)) {
            $clause .= " WHERE $conditions ";
        }
        $sql = "$withPart SELECT *, UNIX_TIMESTAMP(publicationDate)
                AS publicationDate
                $fromPart $clauseJoin $clause
                ORDER BY  $order  LIMIT $offset :numRows";

        $st = $this->pdo->prepare($sql);

        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);

        if(!empty($active))
            $st->bindValue(":active", $active, PDO::PARAM_INT);

        if ($categoryId)
            $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT);

        if ($subcategoryId)
            $st->bindValue( ":subcategoryId", $subcategoryId, PDO::PARAM_INT);

        if ($userId)
            $st->bindValue( ":userId", $userId, PDO::PARAM_INT);

        $st->execute();
        $list = array();

        while ($row = $st->fetch()) {

            $row['authors'] = self::getAuthors($row['id']);
            $article = new Article($row, false);
            $list[] = $article;
        }

        // Получаем общее количество статей, которые соответствуют критерию
//        $sql = "SELECT COUNT(*) AS totalRows $fromPart $clause";
//        $st = $conn->prepare($sql);
//        if(!empty($active))
//            $st->bindValue(":active", $active, PDO::PARAM_INT);
//        if ($categoryId)
//            $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT);
//        if ($subcategoryId)
//            $st->bindValue( ":subcategoryId", $subcategoryId, PDO::PARAM_INT);
//        if ($userId)
//            $st->bindValue( ":userId", $userId, PDO::PARAM_INT);
//        $st->execute();
//        $totalRows = $st->fetch();
//        $conn = null;

        return (array(
            "results" => $list,
//            "totalRows" => $totalRows[0]
            )
        );
    }


    /**
     * Получаем из базы данных имена авторов статьи по ID статьи
     */
    public function getAuthors($id)
    {
        $sql = "SELECT u.* FROM users u JOIN users_articles ua ON u.id = ua.userId "
                . "where ua.articleId = :id";

        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $authors = array();

        while ($row = $st->fetch()) {
            $author = new ExampleUser($row);
            $authors[] = $author;
        }

        return $authors;
    }


    /**
    * Возвращаем объект статьи соответствующий заданному ID статьи
    *
    * @param int ID статьи
    * @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
    */
    public function getById($id, $tableName = '') {

        $sql = 'WITH table_all_views as (select vc.articleId, SUM(vc.views) '
             . 'as all_views from view_counter vc WHERE vc.articleId = :id group by vc.articleId), '
             . 'table_unique_views as (select vc.articleId, COUNT(*) '
             . 'as unique_views from view_counter vc WHERE vc.articleId = :id group by vc.articleId) '
             . 'SELECT a.*, uv.*, av.*, pa.id AS previousArticleId, UNIX_TIMESTAMP(a.publicationDate) '
             . 'AS publicationDate FROM articles AS a '
             . 'LEFT JOIN table_unique_views AS uv ON uv.articleId = a.id '
             . 'LEFT JOIN table_all_views AS av ON av.articleId = a.id '
             . 'LEFT JOIN articles AS pa ON a.id = pa.nextArticleId '
             . 'WHERE a.id = :id LIMIT 1';

        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();

        if ($row) {

            $row['authors'] = self::getAuthors($id);
            $article = new Article($row);

            return $article;
        }
    }


    /**listArticlesIdTitle
     * Получаем из базы данных список названий и ID статей
     */
    public function getListArticlesIdTitle()
    {
        $sql = "SELECT a.id, a.title  FROM articles a";
        $st = $this->pdo->prepare($sql);
        $st->execute();

        $listArticlesIdTitle = array();
        while ($row = $st->fetch()) {
            $articleIdTitle = new Article($row, false);
            $listArticlesIdTitle[] = $articleIdTitle;
        }
        return $listArticlesIdTitle;
    }


//    public function loadFromArray($arr)
//    {
//
//    echo '<pre>';
//    print_r($arr);
//    echo '</pre>';
//    die();
//
//        if (isset($arr['subcategoryId'])) {
//          $subcategory = Subcategory->getById(id: $arr['subcategoryId']);
//          $categoryId = $subcategory->categoryId;
//          $arr['categoryId'] = $categoryId;
//      }
//
//        $modelClassName = static::class;
//        return new $modelClassName($arr);
//    }


    public function update()
    {


        $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),"
             . " categoryId=:categoryId, subcategoryId=:subcategoryId, title=:title,"
             . " summary=:summary, active=:active, content=:content,"
             . " nextArticleId=:nextArticleId WHERE id = :id";

        $st = $this->pdo->prepare ( $sql );

        $st->bindValue( ":publicationDate", (new \DateTime($this->publicationDate))->getTimestamp(), PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
        $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
        $st->bindValue( ":nextArticleId", $this->nextArticleId, PDO::PARAM_INT );
        $st->execute();

        foreach ($this->getAuthors($this->id) as $authorId) {
            $usersIdFromBD[] = $authorId->id;
        };

        //Массив авторов, которых нужно удалить из БД
        $userIdDel = array_diff($usersIdFromBD, $this->authors);

        foreach ($userIdDel as $userId) {
            $sql = "DELETE FROM users_articles ua "
                    . "WHERE ua.userId = :userId AND ua.articleId = :articleId";

            $st = $this->pdo->prepare ( $sql );

            $st->bindValue( ":userId", $userId, PDO::PARAM_INT );
            $st->bindValue( ":articleId", $this->id, PDO::PARAM_INT );
            $st->execute();
        }

        //Массив авторов, которых нужно добавить в БД
        $userIdAdd = array_diff($this->authors, $usersIdFromBD);

        foreach ($userIdAdd as &$userId) {

            $sql = "INSERT INTO users_articles (userId, articleId) "
                    . "VALUES (:userId, :articleId)";
            $st = $this->pdo->prepare ( $sql );

            $st->bindValue( ":userId", $userId, PDO::PARAM_INT );
            $st->bindValue( ":articleId", $this->id, PDO::PARAM_INT );
            $st->execute();
        }
    }


    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete()
    {


      // Удаляем статью
//        $Article = new Article();
//        $deleteArticle = $Article->loadFromArray($_POST);
//        $deleteArticle->delete();
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
//        die();
//      $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
//      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
//      $st->execute();
//      $conn = null;

//        $st = $this->pdo->prepare("DELETE FROM $this->tableName WHERE id = :id LIMIT 1" );
//        $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
//        $st->execute();

        $st = $this->pdo->prepare("DELETE FROM articles WHERE id = :id LIMIT 1" );
        $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
        $st->execute();
    }
}

