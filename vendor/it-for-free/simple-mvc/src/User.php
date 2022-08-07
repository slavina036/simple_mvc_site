<?php
namespace ItForFree\SimpleMVC;

use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\mvc\Model;

/**
 * Абстрактный класс для работы с данными пользователя.
 *
 * Наследует базовый классс модели, а потому потомки данного класса
 * могут также работать как модели в рамках MVC.
 */
abstract class User extends Model
{
    public $role = null;
    public $userName = null;

    /**
     * для хранения объекта обеспечивающего доступ к сессии
     * @var ItForFree\SimpleMVC\Session
     */
    protected $Session = null;


    public function __construct($data = null)
    {
        $this->Session = Config::getObject('core.session.class');
        $Session = $this->Session;
        if (!empty($Session->session['user']['role'])
                && !empty($Session->session['user']['userName'])) {
            $this->role = $Session->session['user']['role'];
            $this->userName = $Session->session['user']['userName'];
        }
        else {
            $Session->session['user']['role'] = 'guest';
            $Session->session['user']['userName'] = 'guest';
            $this->role = 'guest';
            $this->userName = 'guest';
        }

        parent::__construct($data);
    }

    /**
     * Присваивает данной сессии имя пользователя
     * и роль в соответствии с полученными данными
     *
     * @param srting $login имя пользователя
     * @param string $pass  пароль
     * @return boolean
     */
    public function login($login, $pass)
    {
        if ($this->checkAuthData($login, $pass)) {

            $role = $this->getRoleByUserName($login);
            $this->role =  $role;
            $this->userName = $login;
            $this->Session->session['user']['role'] = $role;
            $this->Session->session['user']['userName'] = $login;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получить роль по имени пользователя
     *
     * @param string $userName
     * @return string
     */
    protected abstract function getRoleByUserName($userName);

    /**
     * Проверяет, можно ли авторизировать пользователя
     *  с данным логином и паролем
     *
     * @param string $login
     * @param string $pass
     * @return boolean
     */
    protected abstract function checkAuthData($login, $pass);

    /**
     * Удаляет из User-а и Сессии данные об актуальной роли и имени пользователя
     */
    public function logout()
    {
        $this->role = "";
        $this->userName = "";
        $this->Session->session['user'] = null;

        return true;
    }

    /**
     *
     * Проверяет разрешено ли данному пользовалю использвать данный маршрут.
     * Если полученный из роутера для данного маршрута контроллер не найден,
     *  то считаем, что маршрут разрешён и не находится
     *  в ведении системы контроля доступа.
     *
     * @param string $route маршрут
     * @return boolean  доступен ли он данном пользователю
     * @throws SmvcUsageException
     */
    public function isAllowed($route)
    {
        $result = true;
        $Router = Config::getObject('core.router.class');

        $controllerName = $Router->getControllerClassName($route);

        if (!class_exists($controllerName)) {
            throw new SmvcUsageException("Контроллер '$controllerName',"
                    . " соответствущий переданному"
                    . " для контроля доступа маршруту '$route' не найден.");
            $result = true;
        } else {

            $controller = new $controllerName();
            $actionName = $Router->getControllerActionName($route);
            $result = $controller->isEnabled($actionName);
        }

        return $result;
    }

    /**
     *
     * @param type $route
     * @param type $elementHTML
     */
    public function returnIfAllowed($route, $elementHTML)
    {
        if($this->isAllowed($route)) {
            echo $elementHTML;
        };
    }


    /**
     * Вернёт массив с выкладкой (пояснением) по параметрам,
     * влияющим на доступ пользоватлея к маршруту
     *
     * @param  string $route  маршрут
     * @return array
     */
    public function explainAccess($route)
    {
        $Router = Config::getObject('core.router.class');
        $role = $this->role;
        $hypoControllerName = $Router->getControllerClassName($route);
        $controllerExists = class_exists($hypoControllerName);
        $actionName = $Router->getControllerActionName($route);
        $methodName = 'имя метода не найдено';
        $methodExists = false;
        $rules = 'правил не найдено';
        $access = 'не  определён';
        $explanation = 'нет пояснения';

        if ($controllerExists) {
            $controller = new $hypoControllerName();
            $rules = $controller->getRules();
            $methodName =  $Router->getControllerMethodName($actionName);
            $methodExists = method_exists($controller, $methodName);
            $access = $controller->isEnabled($actionName);
            $explanation = $controller->explanation;
        }

        $result = [
            'Переданный маршрут' => $route,
            'Роль пользователя' => $role,
            'Гипотетическое имя контроллера:' =>  $hypoControllerName,
            'Имя действия (как в правилах)'  => $actionName,
            'Гипотетическое метода контролллера для данного действия'  => $methodName,
            'Контроллер найден (существует)?' => $controllerExists,
            'Действие контроллера найдено (существует)?' => $methodExists,
            'Правила контроллера:' => $rules,
            'Есть доступ?' => $access,
            'Пояснение системы контроля:' => $explanation,
        ];

        return $result;
    }

}
