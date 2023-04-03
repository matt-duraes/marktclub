<?php

namespace Erro\Retorno;

abstract class SolucaoGeral
{
    protected array $retorno;
    protected function pegarSolucaoGeral($mensagem): array
    {
        $retorno = $this->retorno;
        if (
            is_array($retorno) &&
            isset($retorno['sugestao'], $retorno['sugestao']['titulo']) &&
            !empty($retorno['sugestao']['titulo'])
        ) {
            $sugestao = $retorno['sugestao'];
            return [
                'titulo' => $sugestao['titulo'],
                'texto' => $sugestao['texto'],
                'lista' => $sugestao['sugestao']
            ];
        } elseif (preg_match('/^Undefined property\:/', $mensagem) && preg_match('/\:\:\$/', $mensagem)) {
            $explode = explode('::', $mensagem);
            $propriedade = $explode[1] ?? '';
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);

            return [
                'titulo' => 'A propriedade ' . $propriedade . ' não foi definida.',
                'texto' => 'A propriedade <strong>' . $propriedade . '</strong> não foi definida na class <strong>'
                    . $classe . '</strong> ou foi definida com erro de digitação.',
                'lista' => [
                    'O nome <strong>' . $propriedade . '</strong> está digitado de maneira incorreta.',
                    'A propriedade <strong>' . $propriedade
                        . '</strong> está digitada de maneira incorreta na classe <strong>' . $classe . '</strong>.',
                    'Você não definiu a propriedade <strong>' . $propriedade
                        . '</strong> na classe <strong>' . $classe . '</strong>.'
                ]
            ];
        } elseif (preg_match('/^Undefined variable \$/', $mensagem)) {
            $explode = explode('$', $mensagem);
            $explode = explode(' ', $explode[1]);
            $variavel = $explode[0];
            return [
                'titulo' => 'A variável não foi definida.',
                'texto' => 'A variável ' . $variavel . ' não foi definida antes do seu uso.',
                'lista' => [
                    'Defina a variável <strong>' . $variavel . '</strong> antes do seu uso.',
                    'Remova a variável <strong>' . $variavel . '</strong> caso não for mais usá-la.'
                ]
            ];
        } elseif (preg_match('/^preg_match\(\)\: Compilation failed\: /', $mensagem)) {
            return [
                'titulo' => 'Falha na função preg_match.',
                'texto' => 'Existe um erro na expressão regular da sua função preg_match.'
            ];
        } elseif (preg_match('/^Undefined property\:/', $mensagem) && preg_match('/\:\:\$/', $mensagem)) {
            $explode = explode('::', $mensagem);
            $propriedade = $explode[1] ?? '';
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);

            return [
                'titulo' => 'A propriedade ' . $propriedade . ' não foi definida.',
                'texto' => 'A propriedade <strong>' . $propriedade . '</strong> não foi definida na class <strong>'
                    . $classe . '</strong> ou foi definida com erro de digitação.',
                'lista' => [
                    'O nome <strong>' . $propriedade . '</strong> está digitado de maneira incorreta.',
                    'A propriedade <strong>' . $propriedade
                        . '</strong> está digitada de maneira incorreta na classe <strong>' . $classe . '</strong>.',
                    'Você não definiu a propriedade <strong>' . $propriedade . '</strong> na classe <strong>'
                        . $classe . '</strong>.'
                ]
            ];
        } elseif (preg_match('/^preg_match\(\)\: Compilation failed\: /', $mensagem)) {
            return [
                'titulo' => 'Falha na função preg_match.',
                'texto' => 'Existe um erro na expressão regular da sua função preg_match.'
            ];
        } elseif (
            preg_match(
                '/^Call to undefined function [a-zA-Z0-9\_\\\]\\\view\(\)$/',
                $mensagem
            ) || $mensagem == 'Call to undefined function view()'
        ) {
            return [
                'titulo' => 'Função view não encontrada.',
                'texto' => 'Você tentou chamar a função view mas ela não foi encontrada.',
                'lista' => [
                    'A função <strong>view</strong> só pode ser chamada de um controller.',
                    'Verifique se você estendeu o <strong>\Controller\Controller</strong> a sua controller.',
                ]
            ];
        } elseif (
            preg_match(
                '/^Call to undefined function [a-zA-Z0-9\_\\\]\\\html\(\)$/',
                $mensagem
            ) || $mensagem == 'Call to undefined function html()'
        ) {
            return [
                'titulo' => 'Função html não encontrada.',
                'texto' => 'Você tentou chamar a função html mas ela não foi encontrada.',
                'lista' => [
                    'A função <strong>html</strong> só pode ser chamada de um controller.',
                    'Verifique se você estendeu o <strong>\Controller\Controller</strong> a sua controller.',
                ]
            ];
        } elseif (preg_match('/^Undefined constant/', $mensagem)) {
            $variavel = explode('\\', $mensagem);
            if (count($variavel) == 1) {
                $variavel = explode(' ', $mensagem);
            }
            $variavel = str_replace('"', '', end($variavel));

            return [
                'titulo' => 'A constante ' . $variavel . ' não foi definida.',
                'texto' => 'Nem sempre este erro indica que o problema é uma constante não declarada, podem ter outros problemas relacionados a esse erro.',
                'lista' => [
                    '<strong>' . $variavel . '</strong> pode ser uma constante não declarada.',
                    '<strong>' . $variavel . '</strong> pode ser uma string que não foi colocada entre aspas.',
                    '<strong>' . $variavel . '</strong> pode ser uma variável ao qual você esqueceu de colocar o $ (sifrão).',
                    '<strong>' . $variavel . '</strong> pode ser uma classe ao qual você esqueceu de colocar o new.'
                ]
            ];
        } elseif (preg_match('/^Class /', $mensagem) && preg_match('/not found$/', $mensagem)) {
            $classe = explode('"', $mensagem)[1] ?? '';
            $classe = explode('\\', $classe);
            $classe = count($classe) == 1 ? $classe[0] : end($classe);
            return [
                'titulo' => 'A classe ' . $classe . ' não foi encontrada.',
                'texto' => 'Nem sempre este erro indica que o problema está no arquivo indicado, as vezes o problema pode está na própria classe.',
                'lista' => [
                    'Você não colocou ou colocou errado o "<strong>use</strong>" da classe <strong>'
                        . $classe . '</strong>.',
                    'Você não colocou ou colocou errado o "<strong>namespace</strong>" da classe <strong>'
                        . $classe . '</strong>.',
                    'Você digitou errado o nome da classe <strong>' . $classe . '</strong>.',
                    'Você mudou a classe de diretório e esqueceu de mudar seu "<strong>namespace</strong>".',
                    'Você não criou a classe <strong>' . $classe . '</strong>.'
                ]
            ];
        } elseif (
            preg_match(
                '/^Call to undefined method /',
                $mensagem
            ) && preg_match('/[a-zA-Z0-9\_]+::[a-zA-Z0-9\_]+/', $mensagem)
        ) {
            $explode = explode(' ', $mensagem);
            $explode = explode('::', end($explode));
            $classe = $explode[0];
            $metodo = explode('(', $explode[1] ?? '');
            $metodo = $metodo[0];

            return [
                'titulo' => 'Não foi encontrado o método ' . $metodo . ' na classe ' . $classe . '.',
                'texto' => 'Nem sempre este erro indica que o problema está no arquivo indicado, as vezes o problema pode está na própria classe.',
                'lista' => [
                    'Você digitou o nome errado do método.',
                    'Você não criou o método "<strong>' . $metodo . '</strong>" na classe "<strong>'
                        . $classe . '</strong>".'
                ]
            ];
        } elseif (preg_match('/^Call to private method /', $mensagem)) {
            $explode = explode('::', $mensagem);
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);
            $metodo = explode('(', $explode[1] ?? '');
            $metodo = $metodo[0];

            return [
                'titulo' => 'Sem permissão para acessar o método ' . $metodo . ' na classe ' . $classe . '.',
                'texto' => 'Métodos privados não podem ser acessador de outro local a não ser a própria classe "<strong>' . $classe . '</strong>".',
                'lista' => [
                    'Tente mudar a visibilidade do método.',
                    'Mude a sua regra de negócios para chamar o conteúdo do método "<strong>'
                        . $metodo . '</strong>" de outra forma.'
                ]
            ];
        } elseif (preg_match('/^Call to protected method /', $mensagem)) {
            $explode = explode('::', $mensagem);
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);
            $metodo = explode('(', $explode[1] ?? '');
            $metodo = $metodo[0];

            return [
                'titulo' => 'Sem permissão para acessar o método ' . $metodo . ' na classe ' . $classe . '.',
                'texto' => 'Métodos protegidos não podem ser acessador de outro local a não
                    ser a própria classe ou de classes herdadas.',
                'lista' => [
                    'Tente mudar a visibilidade do método.',
                    'Mude a sua regra de negócios para chamar o conteúdo do método "<strong>'
                        . $metodo . '</strong>" de outra forma.'
                ]
            ];
        } elseif (preg_match('/^Cannot access private property /', $mensagem)) {
            $explode = explode('::', $mensagem);
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);
            $propriedade = $explode[1] ?? '';

            return [
                'titulo' => 'Sem permissão para acessar a propriedade ' . $propriedade . ' da classe ' . $classe . '.',
                'texto' => 'Propriedades privadas não podem ser acessador de outro local a não ser a própria classe.',
                'lista' => [
                    'Tente mudar a visibilidade da propriedade.',
                    'Mude a sua regra de negócios para chamar o conteúdo da propriedade "<strong>'
                        . $propriedade . '</strong>" de outra forma.'
                ]
            ];
        } elseif (preg_match('/^Cannot access protected property /', $mensagem)) {
            $explode = explode('::', $mensagem);
            $classe = explode(' ', $explode[0]);
            $classe = end($classe);
            $propriedade = $explode[1] ?? '';

            return [
                'titulo' => 'Sem permissão para acessar a propriedade ' . $propriedade . ' da classe ' . $classe . '.',
                'texto' => 'Propriedades protegidas não podem ser acessador de outro local a não ser a própria classe ou de classes herdadas.',
                'lista' => [
                    'Tente mudar a visibilidade da propriedade.',
                    'Mude a sua regra de negócios para chamar o conteúdo da propriedade "<strong>'
                        . $propriedade . '</strong>" de outra forma.'
                ]
            ];
        } elseif (preg_match('/^syntax error, unexpected token/', $mensagem)) {
            return [
                'titulo' => 'Erro de sintaxe.',
                'texto' => 'Existe um erro de sintaxe no código, as vezes, esse erro acontece em <strong>linhas anteriores</strong> a informada pelo sistema, procure por erros como falta de ";" (ponto e virgula), string, funções ou métodos aberto mas não fechados entre outros erros de digitação tanto na linha informada como nas anteriores.',
            ];
        } elseif (preg_match('/^Too few arguments to function/', $mensagem)) {
            return [
                'titulo' => 'Erro nos parametros.',
                'texto' => 'Você deixou de passar algum parâmetro obrigatório para a função/método.',
                'lista' => [
                    'Verifique os parâmetros passados para ver se batem com os parâmetros da função/método.',
                    'Transforme o parâmetro da função/método como opcional.'
                ]
            ];
        } elseif (preg_match('/^Call to undefined function/', $mensagem)) {
            $explode = explode('\\', $mensagem);
            if (count($explode) == 1) {
                $explode = explode(' ', $mensagem);
            }
            $explode = explode('(', end($explode));
            $funcao = $explode[0];

            return [
                'titulo' => 'Não foi encontrado a função ' . $funcao . '.',
                'texto' => 'A função <strong>' . $funcao . '</strong> não foi criada no sistema.',
                'lista' => [
                    'Verifique se você digitou o nome da função corretamente.'
                ]
            ];
        } elseif ($mensagem == 'Using $this when not in object context') {
            return [
                'titulo' => 'Uso incorreto do $this.',
                'texto' => 'Você está tentando usar o <strong>$this</strong> fora do contexto de uma classe.',
                'lista' => [
                    'Removar o <strong>$this</strong> e instacie a classe para usar o método/propriedade.'
                ]
            ];
        } elseif (
            (
                preg_match('/^include/', $mensagem) ||
                preg_match('/^require/', $mensagem) ||
                preg_match('/^include_once/', $mensagem) ||
                preg_match('/^require_once/', $mensagem)
            ) &&
            preg_match('/Failed to open stream\: No such file or directory$/', $mensagem)
        ) {
            $arquivo = explode(')', $mensagem);
            $arquivo = explode('(', $arquivo[0])[1] ?? '';
            $nome = pathinfo($arquivo, PATHINFO_BASENAME) ?? '';
            $dir = pathinfo($arquivo, PATHINFO_DIRNAME) ?? '';
            return [
                'titulo' => 'Falha ao incluir arquivo',
                'texto' => 'Não foi possível fazer a inclusão do arquivo <strong>' . $arquivo . '</strong>.',
                'lista' => [
                    'Verifique se o nome <strong>' . $nome . '</strong> está digitado de maneira correta.',
                    'Verifique se o diretório <strong>' . $dir . '</strong> está digitado de maneira correta.'
                ]
            ];
        }
        return [];
    }
}
