<?php

final class DiretorioSalvar
{
    public function __construct(
        private string $nome,
        ?string $pai = null
    ) {
        $this->criarDiretorio($nome, $pai);
    }

    public function retorno()
    {
        return jsonEncode([
            'status' => 'sucesso',
            'dado' => [
                'html' => $this->html()
            ]
        ]);
    }
    private function criarDiretorio(string $nome, ?string $pai)
    {
        $path = ROOT . '/postman';
        if (!empty($pai)) {
            $pai .= '/' . $pai;
        }
        if (!is_dir($path)) {
            mensagemErro('Erro!', 'O diretório pai não foi encontrado.');
        } elseif (file_exists($path . '/' . $nome)) {
            mensagemErro('Erro!', 'O diretório já existe.');
        } elseif (!mkdir($path . '/' . $nome, 0775)) {
            mensagemErro('Erro!', 'Sem permissão para criar o diretório.');
        }
    }
    private function html()
    {
        return '
            <div class="grupo fechado">
                <div class="nome">
                    <i class="pasta">' . iconePasta(15) . '</i>
                    <p>' . $this->nome . '</p>
                    <i class="opcao botao_opcao_grupo">' . iconeOpcao() . '</i>
                </div>
            </div>
        ';
    }
}
