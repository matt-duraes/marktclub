<?php

namespace Erro;

use Exception;
use Symfony\Component\Finder\Finder;

final class StopBurrice
{
    private static string $ROOT = __DIR__ . '/../../';
    private static array $exclude = ['vendor', 'node_modules', 'public', 'files'];
    private static string $esteArquivo = 'src/Erro/StopBurrice.php';

    public static function conflito(): void
    {
        $arquivo = (new Finder())
            ->exclude(self::$exclude)
            ->notPath(self::$esteArquivo)
            ->files()
            ->contains('<<<<<<< HEAD')
            ->in(self::$ROOT);

        self::validarRetorno('Existe conflitos', $arquivo);
    }

    public static function ppe(): void
    {
        $arquivo = (new Finder())
            ->exclude(self::$exclude)
            ->notPath(self::$esteArquivo)
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
            ->in(self::$ROOT);
        self::validarRetorno('Existe PP/PPE/VDE', $arquivo);
    }
    public static function consoleLog(): void
    {
        $arquivo = (new Finder())
            ->exclude(self::$exclude)
            ->exclude('src/Gulpfile')
            ->notPath(self::$esteArquivo)
            ->files()
            ->name('*.js')
            ->notPath('gulpfile.js')
            ->contains('console.log(')
            ->in('resources')
            ->in('views');
        self::validarRetorno('Existe console.log', $arquivo);
    }

    private static function validarRetorno($mensagem, $lista)
    {
        $retorno = [];
        foreach ($lista as $arquivo) {
            if (empty($arquivo->getRelativePathname())) {
                continue;
            }
            $path = $arquivo->getRelativePathname();
            $retorno[$path] = $path;
        }
        if ($retorno) {
            throw new Exception(
                message: "\033[31m\033[1m" . $mensagem . " ಠ_ಠ - Olhar arquivos:\033[0m"
                    . PHP_EOL . implode(PHP_EOL, array_values($retorno)) . PHP_EOL
            );
        }
    }
}
