## [0.x.x] 2021-01-21


* Избавились от  подписки класса  `ItForFree\SimpleMVC\ExceptionHandler` (в его конструкторе вызовом функции [set_exception_handler](https://www.php.net/manual/ru/function.set-exception-handler.php)) на обработку всех ошибок, 
потому что  сигнатура его же метода `ExceptionHandle::handleException(\Exception $exception)`
не подразумевает обработку ошибок класса `Error` (к которым относятся напр. фатальные ошибки).


## [0.0.2] 2021-02-01

* Делаем `ItForFree\SimpleMVC\User` обычным классом, без использования паттерна Одиночка (Singleton),
 это позволит нормально наследовать от него другие классы, которым нужен открытый конструктор.

## [0.0.3] 2022-02-13

* Добавлена поддержка инъекции зависимостей контейнером