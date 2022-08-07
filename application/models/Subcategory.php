<?php

namespace application\models;

use PDO;

/**
 * Класс для обработки подкатегорий статей
 */

class Subcategory extends \ItForFree\SimpleMVC\mvc\Model
{
    // Свойства

    /**
    * @var int ID подкатегории из базы данных
    */
    public $id = null;

    /**
    * @var string Название  подкатегории
    */
    public $name = null;

    /**
    * @var int ID категории из базы данных
    */
    public $categoryId = null;

    /**
    * @var string Название  категории
    */
    public $category_name = null;

    /**
    * @var string Короткое описание подкатегории
    */
    public $description = null;


    /**
    * Возвращаем все (или диапазон) объектов Subcategory из базы данных
    */
    public function getList(int $numRows = 1000000, $categoryId = null,
                                       $order = null, $active = null,
                                       $subcategoryId = null, $pageCurrent = 1,
                                       $userId = null, bool $countViews = true)
    {
        if (empty($order)) {
            $order = "name ASC";
        }
        $fromPart = "FROM subcategories sc JOIN categories c ON sc.categoryId = c.id";
        $sql = "SELECT sc.*, c.name AS category_name $fromPart
                ORDER BY $order LIMIT :numRows";
        $st = $this->pdo->prepare($sql);
        $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
        $st->execute();
        $list = array();

        while ( $row = $st->fetch() ) {
          $subcategory = new Subcategory($row);
          $list[] = $subcategory;
        }

        return (array('results' => $list));
    }


    /**
    * Возвращаем объект Subcategory, соответствующий заданному ID
    */
    public function getById($id, $tableName = '')
    {
        $sql = "SELECT * FROM subcategories WHERE id = :id";

        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();

        if ($row)
            return new Subcategory($row);
    }


    /**
    * Вставляем текущий объект Subategory
    */
    public function insert()
    {
        $sql = "INSERT INTO subcategories (name, categoryId, description) "
                . "VALUES (:name, :categoryId, :description)";
        $st = $this->pdo->prepare ($sql);
        $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
        $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_STR );
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
    }


    /**
    * Обновляем текущий объект Subcategory в базе данных.
    */
    public function update()
    {
      // Обновляем подкатегорию
      $sql = "UPDATE subcategories SET name=:name, categoryId=:categoryId, description=:description WHERE id = :id";
      $st = $this->pdo->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
    }


    /**
    * Удаляем текущий объект Subcategory из базы данных.
    */
    public function delete()
    {
      $st = $this->pdo->prepare( "DELETE FROM subcategories WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
    }
}