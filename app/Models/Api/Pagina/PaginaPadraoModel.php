<?php

namespace App\Models\Api\Pagina;

use Closure;

abstract class PaginaPadraoModel implements PaginaInterface
{
    protected const LOCAL_GERAL = 'geral';
    protected const LOCAL_MOBILE = 'mobile';
    protected const LOCAL_DESKTOP = 'desktop';
    protected const TARGET_SELF = '_self';
    protected const TARGET_BLANK = '_blank';

    protected array $html = [];
    protected array $funcao = [];
    protected array $temp = [];
    protected array $listaLogo = [];

    public function pegarHtml(): array
    {
        return $this->html;
    }

    /**
     * Cria uma nova sessão
     *
     * @param  Closure $funcao Função com os métodos que deseja executar
     * @param  string  $local  Local que irá aparecer
     * @return self
     */
    protected function sessao(Closure $funcao, string $local = self::LOCAL_GERAL): self
    {
        $this->validarLocal($local);
        call_user_func($funcao);
        $this->html[] = [
            'tipo'  => 'sessao',
            'local' => $local,
            'lista' => $this->funcao
        ];
        $this->funcao = [];
        return $this;
    }

    protected function passoPasso(Closure $funcao, string $local = self::LOCAL_GERAL): self
    {
        $this->validarLocal($local);
        call_user_func($funcao);
        $funcaoFinal = $this->temp;
        $quantidade = count($funcaoFinal);
        $lista = [];
        for ($i = 0; $i < $quantidade; ++$i) {
            $lista[] = $funcaoFinal[$i];
            if ($i == $quantidade - 1) {
                break;
            }
            $lista[] = [
                'tipo'  => 'bola',
                'local' => $local,
                'icone' => '<svg height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M16,967.1c-0.1-0.6-0.3-1.2-0.7-1.6L5.1,953.4c-1-1.2-2.8-1.4-4-0.4c-1.2,1-1.4,2.8-0.4,4c0,0,0.1,0.1,0.1,0.1l8.7,10.2 l-8.7,10.2c-1.1,1.2-1,3,0.2,4.1s3,1,4-0.2c0,0,0.1-0.1,0.1-0.1l10.2-12.1C15.8,968.6,16.1,967.9,16,967.1L16,967.1z"></path></g></svg>'
            ];
        }
        $this->funcao[] = [
            'tipo'  => 'passo_passo',
            'local' => $local,
            'lista' => $lista
        ];
        $this->temp = [];
        return $this;
    }

    protected function passoPassoItem(string $icone, string $texto): self
    {
        $this->temp[] = [
            'tipo'  => 'passo_passo_item',
            'icone' => $icone,
            'texto' => $texto
        ];
        return $this;
    }

    protected function bloco(Closure $funcao, string $local = self::LOCAL_GERAL): self
    {
        $this->validarLocal($local);
        call_user_func($funcao);
        $funcaoFinal = $this->temp;

        $this->funcao[] = [
            'tipo'  => 'bloco',
            'local' => $local,
            'lista' => $funcaoFinal
        ];

        $this->temp = [];
        return $this;
    }

    protected function blocoItem(string $texto, string $local = self::LOCAL_GERAL): self
    {
        $this->temp[] = [
            'tipo'  => 'bloco_item',
            'local' => $local,
            'texto' => $texto
        ];
        return $this;
    }

    protected function blocoBotao(
        string $texto,
        string $link = null,
        string $target = null,
        string $acao = null,
        string $local = self::LOCAL_GERAL
    ): self {
        $botao = [
            'tipo'  => 'bloco_botao',
            'local' => $local,
            'texto' => $texto,
        ];

        if (!empty($link)) {
            $this->validarLink($link);
            $botao['link'] = $link;
            $botao['target'] = $this->pegarTarget($target);
        }

        if (!empty($acao)) {
            $botao['acao'] = $acao;
        }

        $this->temp[] = $botao;
        return $this;
    }

    /**
     * Cria um lista com logo
     *
     * @param  Closure $funcao Função com os métodos que deseja executar
     * @param  string  $local  Local que irá aparecer
     * @return self
     */
    protected function listaLogo(Closure $funcao, string $local = self::LOCAL_GERAL): self
    {
        $this->validarLocal($local);
        call_user_func($funcao);
        $this->funcao[] = [
            'tipo'  => 'lista-logo',
            'local' => $local,
            'lista' => $this->listaLogo
        ];
        $this->listaLogo = [];
        return $this;
    }

    /**
     * Imagem de logo
     *
     * @param  string      $imagem Link da imagem
     * @param  string|null $titulo Título para a imagem
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @param  string      $local  Local que irá aparecer
     * @return self
     */
    protected function logo(
        string $imagem,
        string $titulo = null,
        string $link = null,
        string $target = null,
        string $local = self::LOCAL_GERAL
    ): self {
        $this->validarLocal($local);
        $this->validarLink($imagem);
        $dado = [
            'tipo'   => 'logo',
            'local'  => $local,
            'imagem' => $imagem
        ];
        if (!empty($titulo)) {
            $dado['titulo'] = $titulo;
        }
        if (!empty($link)) {
            $this->validarLink($link);
            $dado['link'] = $link;
            $dado['target'] = $this->pegarTarget($target);
        }
        $this->listaLogo[] = $dado;
        return $this;
    }

    /**
     * Adiciona uma observação
     *
     * @param  string|null $titulo Título da observação
     * @param  string|null $texto  Texto da observação
     * @param  string      $local  Local que irá aparecer
     * @return self
     */
    protected function observacao(string $titulo = null, string $texto = null, string $local = self::LOCAL_GERAL): self
    {
        $this->validarLocal($local);
        if (empty($titulo) && empty($texto)) {
            mensagemErro('Erro!', 'Você tem que passar um título e/ou texto para a observação.');
        }
        $dado = [
            'tipo'  => 'observacao',
            'local' => $local,
        ];
        if (!empty($titulo)) {
            $dado['titulo'] = $titulo;
        }
        if (!empty($texto)) {
            $dado['texto'] = $texto;
        }
        $this->funcao[] = $dado;
        return $this;
    }

    /**
     * Adiciona um banner
     *
     * @param  string      $imagem Link da imagem
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @param  string      $local  Local que irá aparecer
     * @return self
     */
    protected function banner(
        string $imagem,
        string $link = null,
        string $target = null,
        string $local = self::LOCAL_GERAL
    ): self {
        $this->validarLocal($local);
        $this->validarLink($imagem);
        $dado = [
            'tipo'   => 'banner',
            'local'  => $local,
            'imagem' => $imagem
        ];

        if (!empty($link)) {
            $this->validarLink($link);
            $dado['link'] = $link;
            $dado['target'] = $this->pegarTarget($target);
        }
        $this->funcao[] = $dado;
        return $this;
    }

    /**
     * Adiciona um banner mobile
     *
     * @param  string      $imagem Link da imagem
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @return self
     */
    protected function bannerMobile(string $imagem, string $link = null, $target = null): self
    {
        $this->banner($imagem, $link, $target, self::LOCAL_MOBILE);
        return $this;
    }

    /**
     * Adiciona um banner desktop
     *
     * @param  string      $imagem Link da imagem
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @return self
     */
    protected function bannerDesktop(string $imagem, string $link = null, $target = null): self
    {
        $this->banner($imagem, $link, $target, self::LOCAL_DESKTOP);
        return $this;
    }

    /**
     * Adiciona um título
     *
     * @param  string $texto Texto para o titulo
     * @param  string $local Local que irá aparecer
     * @return self
     */
    protected function titulo(string $texto, string $local = self::LOCAL_GERAL): self
    {
        $this->textoGeral('titulo', $texto, $local);
        return $this;
    }

    /**
     * Adiciona um subtítulo
     *
     * @param  string $texto Texto para o titulo
     * @param  string $local Local que irá aparecer
     * @return self
     */
    protected function subtitulo(string $texto, string $local = self::LOCAL_GERAL): self
    {
        $this->textoGeral('subtitulo', $texto, $local);
        return $this;
    }

    /**
     * Adiciona um texto
     *
     * @param  string $texto Texto para o titulo
     * @param  string $local Local que irá aparecer
     * @return self
     */
    protected function texto(string $texto, string $local = self::LOCAL_GERAL): self
    {
        $this->textoGeral('texto', $texto, $local);
        return $this;
    }

    /**
     * Adiciona um botão normal
     *
     * @param  string      $texto  Texto do botão
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @param  string      $local  Local que irá aparecer
     * @return self
     */
    protected function botao(
        string $texto,
        string $link,
        string $target = null,
        string $local = self::LOCAL_GERAL
    ): self {
        $this->botaoGeral($texto, $link, $target, $local, 'botao');
        return $this;
    }

    /**
     * Adiciona um botão de destaque
     *
     * @param  string      $texto  Texto do botão
     * @param  string|null $link   Link para onde o usuário será enviado
     * @param  string|null $target Target do link
     * @param  string      $local  Local que irá aparecer
     * @return self
     */
    protected function botaoDestaque(
        string $texto,
        string $link,
        string $target = null,
        string $local = self::LOCAL_GERAL
    ): self {
        $this->botaoGeral($texto, $link, $target, $local, 'botao-destaque');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function botaoGeral(
        $texto,
        $link,
        $target,
        $local,
        $tipo
    ) {
        $this->validarLocal($local);
        $dado = [
            'tipo'  => $tipo,
            'local' => $local,
            'texto' => $texto,
        ];
        if (!empty($link)) {
            $this->validarLink($link);
            $dado['link'] = $link;
            $dado['target'] = $this->pegarTarget($target);
        }
        $this->funcao[] = $dado;
        return $dado;
    }

    private function textoGeral($tipo, $texto, $local)
    {
        $this->validarLocal($local);
        $this->funcao[] = [
            'tipo'  => $tipo,
            'local' => $local,
            'texto' => $texto
        ];
    }

    private function validarLocal(string $local): void
    {
        if (in_array($local, [self::LOCAL_DESKTOP, self::LOCAL_GERAL, self::LOCAL_MOBILE])) {
            return;
        }
        mensagemErro(mensagem: 'Local inválido.');
    }

    private function validarLink(string $link): void
    {
        if (validarUrl($link)) {
            return;
        }
        mensagemErro('Erro!', 'A string ' . $link . ' não é um link válido.');
    }

    private function pegarTarget(string $target = null)
    {
        return in_array($target, [self::TARGET_BLANK, self::TARGET_SELF]) ? $target : '_self';
    }
}
