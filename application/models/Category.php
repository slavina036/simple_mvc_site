<?php

namespace application\models;

use PDO;

/**
 * Класс для обработки категорий статей
 */

class Category extends \ItForFree\SimpleMVC\mvc\Model
{
    // Свойства

    /**
    * @var int ID категории из базы данных
    */
    public $id = null;

    /**
    * @var string Название категории
    */
    public $name = null;

    /**
    * @var string Короткое описание категории
    */
    public $description = null;


    /**
    * Возвращаем все (или диапазон) объектов Category из базы данных
    *
    * @param int Optional Количество возвращаемых строк (по умолчаниюt = all)
    * @param string Optional Столбец, по которому сортируются категории(по умолчанию = "name ASC")
    * @return Array|false Двух элементный массив: results => массив с объектами Category; totalRows => общее количество категорий
    */
    public function getList(int $numRows = 1000000, $categoryId = null,
                                $order = null, $active = null,
                                $subcategoryId = null, $pageCurrent = 1,
                                $userId = null, bool $countViews = true)
    {
        if (empty($order)) {
            $order = "name ASC";
        }
        $fromPart = "FROM categories";
        $sql = "SELECT * $fromPart
                ORDER BY $order LIMIT :numRows";

        $st = $this->pdo->prepare($sql);
        $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
        $st->execute();

        $list = array();

        while ( $row = $st->fetch() ) {
          $category = new Category($row, false);
          $list[] = $category;
        }

        return ( array ( "results" => $list) );
    }

    /**
    * Возвращаем объект Category, соответствующий заданному ID
    */
    public function getById($id, $tableName = '')
    {
        $sql = "SELECT * FROM categories WHERE id = :id";

        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();

        if ($row)
            return new Category($row);
    }


    /**
    * Вставляем текущий объект Category в базу данных
    */
    public function insert()
    {
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $st = $this->pdo->prepare ($sql);
        $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
        $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
    }


    /**
    * Обновляем текущий объект Category в базе данных.
    */
    public function update()
    {
        $sql = "UPDATE categories SET name=:name, description=:description WHERE id = :id";
        $st = $this->pdo->prepare ( $sql );
        $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
        $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
        $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
        $st->execute();
    }


    /**
    * Удаляем текущий объект Category из базы данных
    */
    public function delete()
    {
        $st = $this->pdo->prepare("DELETE FROM categories WHERE id = :id LIMIT 1" );
        $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
        $st->execute();
    }
}