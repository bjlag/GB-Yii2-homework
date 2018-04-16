# GB-Yii2-homework
GeekBrains - домашняя работа по курсу Yii2 Framework

## Урок 5. ActiveRecord

Разобраться с доступом к данным БД через объектно-ориентированный интерфейс (ORM).

* Создал миграцию на добавление внешних ключей для удобства генерации модели через Gii.
* С помощью Gii создал модели и контроллеры для работы с таблицами БД note, user и access.
* Создал экшен `UserController::actionTest()`, в котром описал:
    * Создание записи в таблице user с помощью метода `save()`.
    * Создал заметку в таблице note для созданного пользователя с помощью метода `link()`.
    * Добавил запись в промежуточную таблицу access с помощью метода `link()`.
* Создал экшен `UserController::actionLoad()`, в котором:
    * Прочитать из БД все записи из таблицы user применив жадную подгрузку связанных данных из note, с запросами без JOIN, используя метод `with()`.
    * Тоже самое, только c JOIN, используя метод `innerJoinWith()`.
* В моделе User добавил метод `getAccessedNotes()`, связывающий User и Note через релейшен accesses.
* Создал экшен `UserController::actionAccess()`, в котором пробовал добавить доступ пользователю к заметке.

## Урок 4. Отладка, логирование. Работа с БД. Миграция.

Каким образом ведется работа с БД: подключение, выборка данных, добавление данных. Как осуществляется миграция данных. Работа с логами и отладка кода.

* Создал миграцию, в рамках которой создаются 3 таблицы: `user`, `note`, `access`. Так же реализовал возможность отката миграции.
* Добавил экшен `TestController::actionInsert()`, в котором добавляются данные в таблицу user методом `insert()` и в таблицу note методом `batchInsert()`.
+ Добавил экшен `TestController::actionSelect()`, в котором выбираю данные из таблиц `user` и `note`:
    * \yii\db\createCommand: SQL запрос с универсальными кавычками и параметром.
    * \yii\db\Query: все записи с id>1 отсортированные по имени `orderBy()`
    * \yii\db\Query: количество записей `count()`
    * \yii\db\Query: содержимое таблицы `note` с присоединенными по полю `creator_id` записями из таблицы user `innerJoin()`

## Урок 3. Хелперы, атрибуты, валидация и сценарии моделей, формы

Понять что такое хелперы, для чего используются и где хранятся. Изучить каким образом формируются атрибуты модели на основе базовых классов Model и ActiveRecord. Как настраиваются правила валидации атрибутов, для чего нужны сценарии. Каким образом создаются формы.

* Настроил правила валидации атрибута `name` и `price` в методе `Product::rules()`.
    * **name** - длина до 20 символов, необходимо обрезать пробелы `trim` и вырезать тэги `strip_tags()`.
    * **price** - только целые числа больше 0 и меньше 1000.
* Добавил метод `Product::scenarios()`, в котором указал для сценария по умолчанию `SCENARIO_DEFAULT`, что активный атрибут только `name`.
* В форме модели `Product` поле `name` сделал через выпадающий список.
* Добавил отдельные сценарии в модели `Product` на измнение и добавление данных.
    * **SCENARIO_CREATE** - активные атрибуты `name`, `price`, `created_at`.
    * **SCENARIO_UPDATE** - активные атрибуты `price`, `created_at`.

## Урок 2. БД, базовые классы, Gii

Изучить каким образом подключаться в БД. Какие есть базовые классы, какие возможности они дают, магические методы __set(), __get(), __call(). Как генерировать код через Gii. Что такое виджеты.

* Разобрался на базе компонента `TestService`, как создаются компоненты, каким образом их прописать в конфиге, как вызываются.
* Настроил подключение к БД. 
* Создал таблицу `product`. 
* C помощью генератора кода `Gii` создал модель `Product`, которая описывает работу с таблицей БД `product`. 
* C помощью `Gii` сгенерировал CRUD-методы для работы с моделью `Product`.
* Изменил вывод виджета `DetailView` во вьюхе _/views/product/view.php_.
* Изменил вывод виджета `GridView` во вьюхе _/views/product/index.php_.

## Урок 1. Настройка рабочей среды с помощью Vagrant, шаблоны Yii

Разобраться каким образом настраивается среда разработки через Vagrant. Изучить архитектуру приложения Yii2, какие бывают шаблоны. 

* Развернул виртуальную машину c настроенным сервером с помощью Vagrant файла проекта yii-app-basic.
* Создал контроллер TestController с одним методом actionIndex.
* Создал вьюху "/views/test/index.php" для вывода данных метода actionIndex.
* Добавил в меню пункт меню на новый контроллер.
* Настроил ЧПУ.
* Создал модель Product.
* Создал несколько товаров на базе класса Product и вывел их в цикле во вьюхе через прямой доступ к полям/атрибутам и через виджет DetailView.
