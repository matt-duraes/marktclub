<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

set_exception_handler('exceptionHandler');

function exceptionHandler($error): void
{
    echo $error->getMessage();
    exit();
}

$exclude = ['vendor', 'node_modules', 'public', 'files'];
$ROOT = __DIR__ . '/../..';
$esteArquivo = 'src/Files/stop-burrice.php';

$finder = new Finder();
$conflito = $finder
    ->exclude($exclude)
    ->notPath($esteArquivo)
    ->files()
    ->contains('<<<<<<< HEAD')
    ->in($ROOT);

$listaConflito = [];
foreach ($conflito as $arquivo) {
    if (empty($arquivo->getRealPath())) {
        continue;
    }
    $path = $arquivo->getRealPath();
    $listaConflito[$path] = $path;
}
if ($listaConflito) {
    throw new Exception(message: "\033[31m\033[1mExiste um conflito ಠ_ಠ" . PHP_EOL . "\033[0mOlhar arquivos:" . PHP_EOL . implode(PHP_EOL, array_values($listaConflito)) . PHP_EOL);
}

$ppe = $finder
    ->exclude($exclude)
    ->notPath($esteArquivo)
    ->notPath('src/Function/FW.func.php')
    ->files()
    ->name('*.php')
    ->name('*.view')
    ->contains('/(\ |\;)ppe\(/')
    ->contains('/[^a-z0-9A-Z]ppe\(/')
    ->contains('/(\ |\;)pp\(/')
    ->contains('/[^a-z0-9A-Z]pp\(/')
    ->contains('/(\ |\;)vde\(/')
    ->contains('/[^a-z0-9A-Z]vde\(/')
    ->in($ROOT);


$listaPpe = [];
foreach ($ppe as $arquivo) {
    if (empty($arquivo->getRealPath())) {
        continue;
    }
    $path = $arquivo->getRealPath();
    $listaPpe[$path] = $path;
}
if ($listaPpe) {
    throw new Exception(message: "\033[31m\033[1mExiste PP/PPE/VDE ಠ_ಠ" . PHP_EOL . "\033[0mOlhar arquivos:" . PHP_EOL . implode(PHP_EOL, array_values($listaPpe)) . PHP_EOL);
}

$consoleLog = $finder
    ->files()
    ->name('*.js')
    ->notPath('gulpfile.js')
    ->exclude('src/Gulpfile')
    ->contains('console.log(')
    ->in('resources')->in('views');

$listaConsoleLog = [];
foreach ($consoleLog as $arquivo) {
    if (empty($arquivo->getRealPath())) {
        continue;
    }
    $path = $arquivo->getRealPath();
    $listaConsoleLog[$path] = $path;
}
if ($listaConsoleLog) {
    throw new Exception(message: "\033[31m\033[1mExiste console.log ಠ_ಠ" . PHP_EOL . "\033[0mOlhar arquivos:" . PHP_EOL . implode(PHP_EOL, array_values($listaConsoleLog)) . PHP_EOL);
}
