<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Application;

if (!Loader::includeModule('iblock')) {
    ShowError("Модуль iblock не подключен");
    return;
}

$request = Context::getCurrent()->getRequest();
$priceMin = (int)$request->getQuery("price_min");
$priceMax = (int)$request->getQuery("price_max");

// Всегда инициализируем объект пагинации
$nav = new PageNavigation("catalog");
$nav->allowAllRecords(false)
    ->setPageSize(5)
    ->initFromUri();

$page = $nav->getCurrentPage();
$pageSize = $nav->getPageSize();

$filter = [
    'IBLOCK_ID' => 5,
    'ACTIVE' => 'Y',
];

if ($priceMin > 0) {
    $filter['>=PROPERTY_PRICE'] = $priceMin;
}
if ($priceMax > 0) {
    $filter['<=PROPERTY_PRICE'] = $priceMax;
}

// Кэш
$cacheTime = 3600;
$cacheId = 'catalog_list_' . md5(serialize([$filter, $page, $pageSize]));
$cacheDir = '/catalog_list/';
$cache = new \CPHPCache();

if ($cache->InitCache($cacheTime, $cacheId, $cacheDir)) {
    $vars = $cache->GetVars();
    $items = $vars['items'];
    $totalCount = $vars['totalCount'];
} elseif ($cache->StartDataCache()) {
    $items = [];

    $res = \CIBlockElement::GetList(
        ['SORT' => 'ASC'],
        $filter,
        false,
        [
            "iNumPage" => $page,
            "nPageSize" => $pageSize,
        ],
        ['ID', 'NAME', 'DETAIL_PICTURE', 'PROPERTY_PRICE', 'PROPERTY_PHOTO']
    );

    while ($ob = $res->GetNextElement()) {
        $fields = $ob->GetFields();
        $fields['PHOTO_SRC'] = \CFile::GetPath($fields['PROPERTY_PHOTO_VALUE']);
        $fields['DETAIL_PICTURE_SRC'] = \CFile::GetPath($fields['DETAIL_PICTURE']);
        $fields['PROPERTY_PRICE_VALUE'] = $fields['PROPERTY_PRICE_VALUE'] ?? '—';
        $items[] = $fields;
    }

    // Получаем общее количество элементов
    $resCount = \CIBlockElement::GetList([], $filter, false, false, ['ID']);
    $totalCount = $resCount->SelectedRowsCount();
    
    $cache->EndDataCache([
        'items' => $items,
        'totalCount' => $totalCount,
    ]);
}

// Устанавливаем количество записей для пагинации
$nav->setRecordCount($totalCount);

// Выводим результат
$this->arResult = [
    'ITEMS' => $items,
    'NAV_OBJECT' => $nav,
];
$this->includeComponentTemplate();
