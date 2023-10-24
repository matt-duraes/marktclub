<?php

namespace App\Models\Api\Pagina;

use App\Models\Api\Trait\BuscarClienteTrait;
use Http\Request;

final class SamsungModel extends PaginaPadraoModel
{
    use BuscarClienteTrait;

    private string $linkArquivo = LINK_ARQUIVO . '/pagina/samsung';
    private string $link;

    public function __construct(
        private Request $request
    ) {
        $this->setarLink();
        $this->sessao(function () {
            $this
                ->bannerDesktop(
                    imagem: $this->linkArquivo . '/banner_desktop.jpg',
                    link: $this->link,
                    target: self::TARGET_BLANK
                )
                ->bannerMobile(
                    imagem: $this->linkArquivo . '/banner_mobile.jpg',
                    link: $this->link,
                    target: self::TARGET_BLANK
                )
                ->passoPasso(function () {
                    $this
                        ->passoPassoItem(
                            icone: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="25"><path d="M20 22H18V20C18 18.3431 16.6569 17 15 17H9C7.34315 17 6 18.3431 6 20V22H4V20C4 17.2386 6.23858 15 9 15H15C17.7614 15 20 17.2386 20 20V22ZM12 13C8.68629 13 6 10.3137 6 7C6 3.68629 8.68629 1 12 1C15.3137 1 18 3.68629 18 7C18 10.3137 15.3137 13 12 13ZM12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"></path></svg>',
                            texto: 'Verifique seu e-mail cadastrado.'
                        )
                        ->passoPassoItem(
                            icone: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M21 11.646V21a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.354A3.985 3.985 0 0 1 2 9V3a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v6c0 1.014-.378 1.94-1 2.646zm-2 1.228a4.007 4.007 0 0 1-4-1.228A3.99 3.99 0 0 1 12 13a3.99 3.99 0 0 1-3-1.354 3.99 3.99 0 0 1-4 1.228V20h14v-7.126zM14 9a1 1 0 0 1 2 0 2 2 0 1 0 4 0V4H4v5a2 2 0 1 0 4 0 1 1 0 1 1 2 0 2 2 0 1 0 4 0z"></path></svg>',
                            texto: 'Acesse o site da Samsung pelo botão abaixo.'
                        )
                        ->passoPassoItem(
                            icone: '<svg height="18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><path class="st0" d="M36,0H20.8h-1.7H4C1.8,0,0,1.7,0,3.8v22.5C0,28.3,1.8,30,4,30h10.9h4H36c2.2,0,4-1.7,4-3.8V3.8 C40,1.7,38.2,0,36,0z M4,2.6h15.2h1.7H36c0.1,0,0.2,0,0.3,0L20,14.8L3.6,2.7C3.7,2.7,3.8,2.6,4,2.6z M37.2,26.2 c0,0.6-0.5,1.1-1.2,1.1H18.9h-4H4c-0.6,0-1.2-0.5-1.2-1.1V5.4l16.4,12.1c0,0,0,0,0,0v0c0.1,0,0.1,0.1,0.2,0.1c0.1,0,0.1,0.1,0.2,0.1 c0.1,0,0.3,0.1,0.4,0.1c0,0,0,0,0,0s0,0,0,0c0.1,0,0.3,0,0.4-0.1c0.1,0,0.1-0.1,0.2-0.1c0.1,0,0.1-0.1,0.2-0.1v0c0,0,0,0,0,0 L37.2,5.4V26.2z"></path></svg>',
                            texto: 'Digite o mesmo e-mail que está cadastro aqui no site da Samsung.'
                        )
                        ->passoPassoItem(
                            icone: '#123',
                            texto: 'Insira o codigo que recebeu por e-mail no campo informado'
                        );
                })
                ->observacao(
                    // @codingStandardsIgnoreStart
                    texto: 'Desconto já aplicado no site de parceria. Não perca tempo, aproveite e boas compras!<br>O e-mail informado no site da Samsung deve ser o mesmo e-mail do seu cadastro.'
                    // @codingStandardsIgnoreEnd
                )
                ->bloco(function () {
                    $this
                        ->blocoItem('Seus e-mails cadastrados são:')
                        ->blocoItem($this->pegarTextoEmail())
                        ->blocoBotao(
                            texto: 'Atualizar e-mail',
                            acao: 'atualizar-email'
                        );
                })
                ->botaoDestaque(texto: 'Acessar site', link: $this->link, target: self::TARGET_BLANK);
        });
    }

    private function pegarTextoEmail()
    {
        $emails = $this->pegarEmails();

        if (empty($emails)) {
            return 'Você não possui e-mail cadastrado.';
        }

        $texto = '';
        switch (count($emails)) {
            case 1:
                $texto = $emails[0];
                break;
            case 2:
                $texto = $emails[0] . ' e ' . $emails[1];
                break;
        }

        return $texto;
    }

    private function pegarEmails()
    {
        $dado = $this->pegarCliente(['cod', $this->request->usuario]);

        $email = [];
        if (!empty($dado->email_pessoal)) {
            $email[] = $dado->email_pessoal;
        }
        if (!empty($dado->email_trabalho)) {
            $email[] = $dado->email_trabalho;
        }
        return $email;
    }

    private function setarLink()
    {
        $this->link = 'https://parcerias.samsung.com.br/markt-club';
    }
}
