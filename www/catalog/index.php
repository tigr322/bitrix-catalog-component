<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Список товаров");
?>

<!-- Подключаем Bootstrap вне PHP -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<?php
$APPLICATION->IncludeComponent("tigran:catalog.list", ".default", []);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
