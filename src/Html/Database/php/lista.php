<?php

$array = array_diff(scandir(__DIR__ . '/../../../../database'), ['.', '..']);

if (!$array) {
    return [];
}

$lista = [];
foreach ($array as $arquivo) {
    if (file_exists(__DIR__ . '/../../../../database/' . $arquivo . '/base.php')) {
        $lista[] = $arquivo;
    }
}

if (!$lista) {
    return [];
}

return $lista;
