# Bitrix Catalog Component + Docker

Этот проект содержит кастомный компонент Bitrix `tigran:catalog.list`, который выводит список товаров из инфоблока с поддержкой фильтрации по цене, постраничной навигации и кэширования.

---

## 📁 Размещение компонента

Компонент размещён по пути:

```
local/components/tigran/catalog.list/
```

Содержит:
- `component.php` — основная логика
- `templates/.default/` — шаблон вывода с Bootstrap 4

Для подключения компонента в шаблоне страницы используйте:

```php
$APPLICATION->IncludeComponent(
    "tigran:catalog.list",
    "",
    [
        "IBLOCK_ID" => 5,
        "COUNT" => 10,
        "FILTER" => $_GET
    ]
);
```

---

## ⚙️ Параметры компонента

| Параметр     | Тип     | Описание                                     |
|--------------|---------|----------------------------------------------|
| `IBLOCK_ID`  | int     | ID инфоблока, откуда берутся товары          |
| `COUNT`      | int     | Кол-во элементов на страницу (пагинация)     |
| `FILTER`     | array   | Массив фильтрации, например `$_GET`          |

Фильтрация реализована по цене через `price_min` и `price_max`:

```
/catalog/index.php?price_min=100&price_max=500
```

---

## 🚀 Запуск проекта

### Шаг 1. Клонировать репозиторий

```bash
git clone https://github.com/tigr322/bitrix-catalog-component.git
cd bitrix-catalog-component
```

### Шаг 2. Запустить Docker

```bash
docker-compose up -d
```

Откроется на: [http://localhost:8006](http://localhost:8006)

---

## 🧩 Установка и проверка

1. Перейти по ссылке: `http://localhost:8006`
2. Установить Битрикс, указав БД:
    - Хост: `mysql`
    - Пользователь: `root`
    - Пароль: `root`
    - БД: `bitrix`
3. В админке создать инфоблок с ID = `5`
4. Добавить товары с полями `NAME`, `PRICE`, `DETAIL_PICTURE`
5. Создать страницу `/catalog/index.php` и вставить:

```php
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");

$APPLICATION->IncludeComponent("tigran:catalog.list", "", [
    "IBLOCK_ID" => 5,
    "COUNT" => 10,
    "FILTER" => $_GET
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
```

6. Открыть страницу в браузере:

```
http://localhost:8006/catalog/index.php
(так как в docker-compose.yml стоит порт
  ports:
      - "8006:80" )
```

7. Проверить фильтрацию:

```
http://localhost:8006/catalog/index.php?price_min=100&price_max=500
```

---

## 🛠 Команды

```bash
docker-compose up -d      # Запуск проекта
docker-compose down       # Остановка
docker-compose logs -f    # Логи
```

---

## ✅ Готово!

Проект готов к расширению: добавление сортировки, AJAX, подключения фильтрации по другим полям и т.д.