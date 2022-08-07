<?php

namespace application\models;

/**
 * Пример реализации класса пользователя (реализуем требующие этого методы абстрактные методы)
 * Эту модель наследуем от специального класа-модели User из ядра SimpleMVC
 */
class ExampleUser extends \ItForFree\SimpleMVC\User
{
    /**
     * Проверка авторизационных данных пользователя
     *
     * @param string $login логин
     * @param string $pass  пароль
     * @return boolean      признак успешности
     */

    public $tableName = 'users';

    /**
     * @var string Критерий сортировки строк таблицы
     */
    public $orderBy = 'timestamp DESC';

    /**
     * Логин пользователя
     * @var type
     */
    public $login = null;

    /**
     * Логин пользователя
     * @var type
     */
    public $salt = null;
    /**
     * @var type
     */
    public $pass = null;

    /**
     * @var type
     */
    public $email = null;

    /**
     * @var type
     */
    public $id = null;

    /**
     * @var type
     */
    public $timestamp = null;

    /**
     * @var type
     */
    public $role = null;

    /**
    * @var int Активность пользователя
    */
    public $active = null;


    protected function checkAuthData($login, $pass)
    {
        $result = false;

        $sql = "SELECT salt, pass FROM users WHERE login = :login";
        $st = $this->pdo->prepare($sql);
        $st->bindValue( ":login", $login, \PDO::PARAM_STR);
        $st->execute();
        $siteAuthData = $st->fetch();

        $pass .= $siteAuthData['salt'];
        $passForCheck = password_verify($pass, $siteAuthData['pass']);

        if (isset($siteAuthData['pass'])) {
            if ($passForCheck) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Вернёт id пользователя
     *
     * @return int
     */
    public function getId()
    {
        if ($this->userName !== 'guest'){
            $sql = "SELECT id FROM users where login = :userName";
            $st = $this->pdo->prepare($sql);
            $st -> bindValue( ":userName", $this->userName, \PDO::PARAM_STR );
            $st -> execute();
            $row = $st->fetch();
            return $row['id'];
        } else  {
            return null;
        }
    }

    /**
     * Получить роль по имени пользователя
     *
     * @param string $userName имя пользователя
     * @return string
     */
    protected function getRoleByUserName($userName)
    {
        $sql = "SELECT role FROM users WHERE login = :login";
        $st = $this->pdo->prepare($sql);
        $st->bindValue( ":login", $userName, \PDO::PARAM_STR);
        $st->execute();

        $siteAuthData = $st->fetch();
        if (isset($siteAuthData['role'])) {
            return $siteAuthData['role'];
        }
    }


    public function insert()
    {
        $sql = "INSERT INTO $this->tableName (timestamp, login, salt, pass, role, email) VALUES (:timestamp, :login, :salt, :pass, :role, :email)";
        $st = $this->pdo->prepare ( $sql );
        $st->bindValue( ":timestamp", (new \DateTime('NOW'))->format('Y-m-d H:i:s'), \PDO::PARAM_STMT);
        $st->bindValue( ":login", $this->login, \PDO::PARAM_STR );

        //Хеширование пароля
        $this->salt = rand(0,1000000);
        $st->bindValue( ":salt", $this->salt, \PDO::PARAM_STR );
//        \DebugPrinter::debug($this->salt);

        $this->pass .= $this->salt;
        $hashPass = password_hash($this->pass, PASSWORD_BCRYPT);
//        \DebugPrinter::debug($hashPass);
        $st->bindValue( ":pass", $hashPass, \PDO::PARAM_STR );

        $st->bindValue( ":role", $this->role, \PDO::PARAM_STR );
        $st->bindValue( ":email", $this->email, \PDO::PARAM_STR );
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
    }


    public function update()
    {
//        echo '<pre>';
//        print_r($this);
//        echo '</pre>';
//        die();

        $pass = '';
        $salt = '';

        $sql = "UPDATE $this->tableName SET timestamp=:timestamp, login=:login, $pass $salt role=:role, email=:email  WHERE id = :id";
        $st = $this->pdo->prepare ( $sql );

        $st->bindValue( ":timestamp", (new \DateTime('NOW'))->format('Y-m-d H:i:s'), \PDO::PARAM_STMT);
        $st->bindValue( ":login", $this->login, \PDO::PARAM_STR );

        // Хеширование пароля, если он обновлен
        if (!empty($this->pass)) {
            $this->salt = rand(0,1000000);
            $st->bindValue( ":salt", $this->salt, \PDO::PARAM_STR );
            $this->pass .= $this->salt;

            $hashPass = password_hash($this->pass, PASSWORD_BCRYPT);

            $pass = 'pass=:pass, ';
            $salt = 'salt=:salt, ';

            $st->bindValue( ":salt", $this->salt, \PDO::PARAM_STR );
            $st->bindValue( ":pass", $hashPass, \PDO::PARAM_STR );

        }

        $st->bindValue( ":role", $this->role, \PDO::PARAM_STR );
        $st->bindValue( ":email", $this->email, \PDO::PARAM_STR );
        $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
        $st->execute();
    }


    public function getList(int $numRows = 1000000, $categoryId = null,
                                $order = null, $active = null,
                                $subcategoryId = null, $pageCurrent = 1,
                                $userId = null, bool $countViews = true)
    {
        $sql = "SELECT * FROM users";
        $st = $this->pdo->prepare($sql);
        $st->execute();
        $list = array();

        while ($row = $st->fetch()) {
            $user = new ExampleUser($row, false);
            $list[] = $user;
        }

        return (array("results" => $list));
    }


    /**
    * Возвращаем объект пользователя соответствующий заданному ID
    *
    * @param int ID пользователя
    * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
    */
//    public function getById($id, $tableName = '')
//    {
//        $sql = "SELECT * FROM users WHERE id = :id";
//        $st = $this->pdo->prepare($sql);
//        $st->bindValue(":id", $id, PDO::PARAM_INT);
//        $st->execute();
//        $row = $st->fetch();
//        $User = new ExampleUser($row);
//        return $User;
//    }
}
