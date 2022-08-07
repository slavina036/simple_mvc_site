<?php
namespace ItForFree\SimpleMVC;

use ItForFree\rusphp\PHP\ArrayLib\DotNotation\Dot;
use ItForFree\SimpleMVC\ExceptionHandler;
use ItForFree\SimpleMVC\exceptions\SmvcUsageException;
use ItForFree\SimpleMVC\exceptions\SmvcConfigException;
use ItForFree\rusphp\PHP\Object\ObjectFactory;
use ItForFree\rusphp\PHP\ArrayLib\Structure;

/**
 * Класс-"точка входа" для работы фреймворка SimpleMVC
 * 
 * Реализует паттерн "Одиночка" @see http://fkn.ktu10.com/?q=node/5572
 */
class Application
{
    /**
     * Массив конфигурации приложения
     * 
     * @var ItForFree\rusphp\PHP\ArrayLib\DotNotation\Dot
     */
    protected $config = null;

    /**
     * Кэш контейнера (конфигурируемых компонентов приложения)
     */
    protected $containerElements = [
		'elements' => [],
		'objects' => [],
	];
        
    /**
     * Cкрываем конструктор для того чтобы класс нельзя было создать в обход get() 
     */
    protected function __construct() {}
    
    /**
     * Метод для получения текущего объекта приложения
     * 
     * @staticvar type $instance
     * @return ItForFree\SimpleMVC\Applicaion объект приложения
     */
    public static function get()
    {
        static $instance = null; // статическая переменная
        if (null === $instance) { // проверка существования
            $instance = new static();
        }
        return $instance;
    }
    
    public static function addElementToConteiner($configPath, $element)
    {
        
        self::get()->containerElements['elements'][$configPath] = $element;
    }
	
	public static function addObjectToConteiner($configPath, $object)
    {
        self::get()->containerElements['objects'][$configPath] = $object;
    }
 
    /**
     * Запускает функционал ядра, 
     * именно этот метод должно вызвать приложение, использущее SimpleMVC 
     * для запуска системы
     * 
     * @return $this
     * @throws SmvcCoreException
     */
    public function run() {
        
        $exceptionHandler = new ExceptionHandler();
        try{
            if (!empty($this->config)) {
                $route = $this->getConfigObject('core.url.class')::getRoute();
                /**
		 * @var \ItForFree\SimpleMVC\Router
		 */
                $Router = $this->getConfigObject('core.router.class');
                $Router->callControllerAction($route); // определяем и вызываем нужно действие контроллера
            } else {
                throw new SmvcCoreException('Не задан конфигурационный массив приложения!');
            }

            return $this;   
        } catch (\Exception $exc) {
            $exceptionHandler->handleException($exc);
        }
    }
    
    /**
     * Устанавливает конфигурацию приложения из массива
     * 
     * @param  array $config многомерный массив конфигурации приложения
     * @return $this
     */
    public function setConfiguration($config)
    {
        $this->config = new Dot($config);
        return $this;
    }
    
    /**
     * Вернёт элемент из массива конфигурации приложения
     * 
     * @param string $inConfigArrayPath ключ в виде строки, разделёной точками -- путь в массиве
     * $withException - флаг, кторый определяет омежт ли бросать исключения метод или нет
     * @return mixed
     */
    public static function getConfigElement($inConfigArrayPath, $withException = true)
    {
		if (empty(self::get()->config)) {
            throw new SmvcUsageException('Не задан конфигурационный массив приложения!');
        }
        
        if (isset(self::get()->containerElements['elements'][$inConfigArrayPath])) {
			return self::get()->containerElements['elements'][$inConfigArrayPath];
		} else {
        
			$configValue = self::get()->config->get($inConfigArrayPath);

			if ($withException && is_null($configValue)) {
			   throw new SmvcConfigException("Элемент с данным путём [$inConfigArrayPath]"
					   . " отсутствует в конфигурационном массиве приложения!");
			}
			self::addElementToConteiner($inConfigArrayPath, $configValue);
			return $configValue;
		}
    }
    
    /**
     * Создаст и вернёт объект по его имени из массива
     * 
     * @param string $inConfigArrayPath ключ в виде строки, разделёной точками -- путь в массиве
     * @return mixed                    $a[] = $param;
     */
    public static function getConfigObject($inConfigArrayPath)
    {
        $publicParams = array();
        $constructParams = array();
        $fullClassName = self::getConfigElement($inConfigArrayPath);

        $currentConteiner = self::get()->containerElements;
        if (isset(self::get()->containerElements['objects'][$inConfigArrayPath])) 
		{ 
		    return self::get()->containerElements['objects'][$inConfigArrayPath];
		} else {
            if (!class_exists($fullClassName)) {
                throw new SmvcConfigException("Вы запросили получение экземпляра класса "
                    . "$fullClassName "
                    . " (т.к. он был добавлен в конфиг по адресу $fullClassName),"
                    . " но такой класс не был ранее объявляен, "
                    . "убедитесь в том, что его код подключен "
                    . "до обращения к экземпляру объекта. ");
            }

            $constructParams = self::getCounstractParams($inConfigArrayPath);
            $publicParams = self::getPablicParams($inConfigArrayPath);

            $newObject = static::getInstanceOrSingletone($fullClassName, $constructParams, $publicParams);
            self::addObjectToConteiner($inConfigArrayPath, $newObject);
            return $newObject;
        }
    }
    
    
    protected static function getInstanceOrSingletone(
		$className, 
		$constructParams = [],
		$publicParams = [], 
		$singletoneInstanceAccessStaticMethodName = 'get')
    { 
       $result = null;
       if (\ItForFree\rusphp\PHP\Object\ObjectClass\Constructor::isPublic($className)) {

          if (!empty($constructParams))
          {
             $result = ObjectFactory::createObjectByConstruct($className, $constructParams);
          } else {
               $result = new $className;
          }
	  
	  if (!empty($publicParams)) {
            ObjectFactory::setPublicParams($result, $publicParams);
          } 
	  
       } else {
            $result =  call_user_func($className . '::' 
                . $singletoneInstanceAccessStaticMethodName); 
       }
       
       self::addObjectToConteiner($className, $result);
       return $result;
    }
    
    protected static function getPathParams($PathClassName, $additionPart) 
    {
        
        $pathParams = explode('.', $PathClassName);
        return $pathParams[0] . '.' . $pathParams[1] . '.' . $additionPart;
    }

    protected static function isAlias($param)
    {
        if(strpos($param, '@') === 0) return true;
    }
    
    protected static function getPablicParams($inConfigArrayPath) 
    {
        $publicParams = array();
        $paramsPath = static::getPathParams($inConfigArrayPath, 'params');
        
        if ($paramsPath) { 
            $params = self::getConfigElement($paramsPath, false);
        }

        if (!empty($params)) {
            foreach($params as $param) {
                if (static::isAlias($param)) {
                        $publicParams[substr($param, 1)] = self::getInstanceByAlias($param);
                }
            }
        }
        return $publicParams;                    
    }
    
    protected static function getCounstractParams($inConfigArrayPath)
    {
        $readyCounstractParams = array();
        $pathConstructParams = static::getPathParams($inConfigArrayPath, 'construct');
        if ($pathConstructParams)  {
            $constructParams = self::getConfigElement($pathConstructParams, false);
        }
                            
        if (!empty($constructParams)) 
        {
            foreach($constructParams as $param) {
                if (static::isAlias($param)) {
                        $readyCounstractParams[substr($param, 1)] = self::getInstanceByAlias($param);
                }
            }
        }   
        return $readyCounstractParams; 
    }
    
    
    /*
     * Возвращает объект или элемент, созданный
     * на основе переданного параметр
     */
    public static function getInstanceByAlias($param)
    {
        //возвращает объект или элемент взависимости от того, есть ли в конфиге у переданного пути часть "класс".
        //Вызывает isClassOrSimpleElement как раз для этой проверки
        $pathToTheDesiredElement = Structure::getPathForElementWithValue(self::get()->config, 'alias', $param);
        if(self::isClassOrSimpleElement($pathToTheDesiredElement)) {
            return self::getConfigObject(implode('.', $pathToTheDesiredElement) . '.class');
        } else {
            return self::getConfigElement(implode('.', $pathToTheDesiredElement));
        }
        
            
    }
    
    /*
     * Проверяет: можно ли создать объект класса по переданному пути или нет
     * Возвращает true/false
     */
    public static function isClassOrSimpleElement($paramPath)
    {       
        $pathToClass = implode('.', $paramPath) . '.class';
        $class = self::getConfigElement($pathToClass, false);
        return !empty($class);
    }
}
