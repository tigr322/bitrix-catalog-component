<?php
use Bitrix\Main\UI\PageNavigation;

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$priceMin = htmlspecialchars($request->getQuery("price_min"));
$priceMax = htmlspecialchars($request->getQuery("price_max"));
?>

<!-- Форма фильтра -->
<form method="GET" class="mb-4">
    <div class="form-row">
        <div class="col">
            <input type="number" name="price_min" class="form-control" placeholder="Цена от" value="<?= $priceMin ?>">
        </div>
        <div class="col">
            <input type="number" name="price_max" class="form-control" placeholder="Цена до" value="<?= $priceMax ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Применить</button>
        </div>
    </div>
</form>

<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="row">
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($item['PHOTO_SRC'])): ?>
                        <img src="<?= $item['PHOTO_SRC'] ?>" class="card-img-top" alt="<?= $item['NAME'] ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= $item['NAME'] ?></h5>
                        <p class="card-text">Цена: <?= $item['PROPERTY_PRICE_VALUE'] ?> ₽</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Постраничная навигация -->
    <div class="text-center mt-4">
    <?php
    /** @var \Bitrix\Main\UI\PageNavigation $nav */
    $nav = $arResult['NAV_OBJECT'];
    
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        "", // можно заменить на "", если хочешь шаблон по умолчанию
        [
            "NAV_OBJECT" => $nav,
            "SEF_MODE" => "N",
        ],
        false
    );
    ?>
</div>

<?php else: ?>
    <p>Нет товаров для отображения</p>
<?php endif; ?>
