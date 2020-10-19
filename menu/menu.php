<?php

// Заполняем массив $menu слудующим образом:
// В ключ элемента массива заносим путь к странице,
// а в значение заносим название класса-обработчика обращения к этой странице.

$menu['/'] = 'Controllers\MainPage';
$menu['/api/add'] = 'Controllers\Api\AddLink';
$menu['/api/statistics'] = 'Controllers\Api\Statistics';