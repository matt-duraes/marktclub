<?php

namespace Helpers;

use Exception;
use Erro\Excecao;
use SendGrid\Mail\Mail;

final class EmailHelper
{
    private string $mensagem = '';
    private string $mensagemTexto = '';
    private array $listaEmail = [];

    /**
     * @param String    $host               Host que enviara o e-mail
     * @param Int       $porta              Porta que enviara o e-mail
     * @param String    $usuario            Usuário do e-mail de envio
     * @param String    $senha              Senha do e-mail de envio
     * @param Array     $emailEnvio         Array com nome e e-mail do e-mail de envio. Ex: ['nome', 'email@dominio.com']
     * @param Array     $emailResposta      Array com nome e e-mail do e-mail de resposta. Ex: ['nome', 'email@dominio.com']
     * @param Bool      $debug              Libera o debug
     */
    public function __construct(
        private string $host = '',
        private int $porta = 0,
        private string $login = '',
        private string $senha = '',
        private array $emailEnvio = [],
        private array $emailResposta = [],
        private ?bool $debug = null,
    ) {
        if (empty($this->host)) {
            $this->host = env('MAIL_HOST', '');
        }
        if (empty($this->usuario)) {
            $this->usuario = env('MAIL_USUARIO', '');
        }
        if (empty($this->senha)) {
            $this->senha = env('MAIL_SENHA', '');
        }
        if (empty($this->porta)) {
            $this->porta = env('MAIL_PORTA', '');
        }
        if (is_null($debug)) {
            $this->debug = env('MAIL_DEBUG', false);
        }
        if (empty($this->emailEnvio)) {
            $emailEnvio = env('MAIL_ENVIO', []);
            $this->emailEnvio = is_array($emailEnvio) ? $emailEnvio : [];
        }
        if (empty($this->emailResposta)) {
            $emailResposta = env('MAIL_RESPOSTA', []);
            $this->emailResposta = is_array($emailResposta) ? $emailResposta : [];
        }
    }

    /**
     * Mensagem HTML padrão do sistema
     *
     * @param string        $titulo         Título para o e-mail
     * @param string        $assunto        Assunto do e-mail, geralmente se coloca o nome de quem está enviando
     * @param string        $mensagem       Mensagem que deseja enviar
     * @param null|int      $codigo         Caso seja um e-mail com código
     * @param null|string   $botaoTexto     Caso seja um e-mail com botão
     * @param null|string   $botaoLink      Link para o botão
     * @param null|string   $posMensagem    Caso queira mandar uma mensagem depois do código ou botão
     * @param null|string   $observacao     Caso queira mandar uma observação em destaque
     * @param bool          $privado        Caso queira colocar o texto que esse e-mail e privado
     * @param null|string   $acao           Ação de porque esse e-mail está sendo enviado, para texto livre, começa com "!"
     * @param null|string   $linkBrowser    Link para ver esse e-mail no Browser
     * @param null|string   $linkRemover    Link para deixar de receber esse e-mail
     */
    public function mensagem(
        string $titulo,
        string $assunto,
        string $mensagem,
        ?int $codigo = null,
        ?string $botaoTexto = null,
        ?string $botaoLink = null,
        ?string $posMensagem = null,
        ?string $observacao = null,
        bool $privado = false,
        ?string $acao = null,
        ?string $linkBrowser = null,
        ?string $linkRemover = null,
        ?string $logo = null,
        ?string $cor = null
    ) {
        $acao = !empty($acao) ? trim($acao) : '';
        $HOST = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0] ?? '';
        ob_start();
        require __DIR__ . '/../Html/Email/View/index.php';
        $this->mensagem = ob_get_clean();
        return $this;
    }
    /**
     * Mensagem de texto para quando o e-mail não suportar HTML
     *
     * @param string $mensagem Mensagem que deseja enviar
     * @return Self
     */
    public function mensagemTexto(string $mensagem): self
    {
        $this->mensagemTexto = $mensagem;
        return $this;
    }

    /**
     * Adiciona um e-mail a ser enviado via SMTP
     *
     * @param string            $titulo         Título do e-mail
     * @param string            $nome           Nome de quem vai receber o e-mail
     * @param string            $email          E-mail de quem vai reveber o e-mail
     * @param null|string       $mensagem       Texto para o corpo da mensagem
     * @param null|string       $mensagemTexto  Mensagem sem HTML
     * @param array             $copia          E-mail de cópia nos padroes: ['email1', 'email2'] ou [['Nome 1', 'email1'], ['Nome 2', 'email2']]
     * @param array             $copiaOculta    E-mail de cópia oculta nos padroes: ['email1', 'email2'] ou [['Nome 1', 'email1'], ['Nome 2', 'email2']]
     * @param array             $arquivo        Arquivo em anexo nos padroes: ['arquivo1', 'arquivo2'] ou [['Nome 1', 'arquivo1'], ['Nome 2', 'arquivo2']]
     * @param null|int          $sleep          Tempo que o e-mail deve aguardar para ser enviado
     * @return Self
     */
    public function adicionar(
        string $titulo,
        string $nome,
        string $email,
        ?string $mensagem = null,
        ?string $mensagemTexto = null,
        array $copia = [],
        array $copiaOculta = [],
        array $arquivo = [],
        ?int $sleep = null
    ): self {
        $this->validarHost();
        $this->validarEmailEnvio();
        $this->validarEmailResposta();

        $mensagem = empty($mensagem) ? $this->mensagem : $mensagem;
        $this->validarDado(
            $titulo,
            $nome,
            $email,
            $mensagem,
            $copia,
            $copiaOculta,
            $arquivo
        );

        if (empty($mensagemTexto)) {
            $mensagemTexto = !empty($this->mensagemTexto) ? $this->mensagemTexto : strip_tags($mensagem);
        }

        $dado = [
            'titulo' => $titulo,
            'nome' => $nome,
            'email' => $email,
            'mensagem' => $mensagem,
            'mensagemTexto' => $mensagemTexto,
            'copia' => $copia,
            'copia_oculta' => $copiaOculta,
            'arquivo' => $arquivo,
            'debug' => $this->debug,
            'host' => $this->host,
            'usuario' => $this->usuario,
            'senha' => $this->senha,
            'porta' => $this->porta,
            'email_envio' => $this->emailEnvio,
            'email_resposta' => $this->emailResposta,
            'sleep' => $sleep
        ];
        $this->listaEmail[] = (new CryptHelper())->encode($dado);

        return $this;
    }

    /**
     * Envia a lista de e-mails usando SMTP
     *
     * @return bool retorna true para sucesso ou false para erro
     */
    public function enviar(): bool
    {
        return $this->curl();
    }

    /**
     * Enviar a lista de e-mail usando o sendGrid
     *
     * @param string        $titulo         Título para o e-mail
     * @param string        $nome           Nome de quem vai receber o e-mail
     * @param string        $email          E-mail de quem vai receber o e-mail
     * @param null|string   $mensagem       Mensagem do e-mail, se null, pega o this->mensagem
     * @param null|string   $mensagemTexto  Mensagem em texto puro, se null, pegar a mensagem e converte para texto
     * @param array         $copia          Lista de email para enviar como copia. [email1, email2] ou [[nome1, email1],[nome2, email2]]
     * @param array         $copiaOculta    Lista de email para enviar como copia oculta. [email1, email2] ou [[nome1, email1],[nome2, email2]]
     * @param array         $arquivo        Arquivo para anexar ao email. [arquivo1, arquivo2] ou [[nome1, arquivo1],[nome2, arquivo2]]
     * @param null|string   $deNome         Nome de quem está enviando o e-mail, se null, pegar do env
     * @param null|string   $deEmail        E-mai de quem está enviando o e-mail, se null, pegar do env
     */
    public function sendGrid(
        string $titulo,
        string $nome,
        string $email,
        ?string $mensagem = null,
        ?string $mensagemTexto = null,
        array $copia = [],
        array $copiaOculta = [],
        array $arquivo = [],
        ?string $deNome = null,
        ?string $deEmail = null
    ) {
        $this->validarEmailEnvio();

        $mensagem = empty($mensagem) ? $this->mensagem : $mensagem;
        $this->validarDado(
            $titulo,
            $nome,
            $email,
            $mensagem,
            $copia,
            $copiaOculta,
            $arquivo
        );

        if (empty($mensagemTexto)) {
            $mensagemTexto = !empty($this->mensagemTexto) ? $this->mensagemTexto : strip_tags($mensagem);
        }

        $deNome = !empty($deNome) ? $deNome : $this->emailEnvio[0];
        $deEmail = !empty($deEmail) && validarEmail($deEmail) ? $deEmail : $this->emailEnvio[1];

        $Email = new Mail();
        $Email->setFrom($deEmail, $deNome);
        $Email->setSubject($titulo);
        $Email->addTo($email, $nome);
        $Email->addContent('text/plain', $mensagemTexto);
        $Email->addContent('text/html', $mensagem);
        if ($copia) {
            foreach ($copia as $lista) {
                if (is_array($lista)) {
                    $Email->addCc($lista[1], $lista[0]);
                } else {
                    $Email->addCc($lista);
                }
            }
        }
        if ($copiaOculta) {
            foreach ($copiaOculta as $lista) {
                if (is_array($lista)) {
                    $Email->addBcc($lista[1], $lista[0]);
                } else {
                    $Email->addBcc($lista);
                }
            }
        }
        if ($arquivo && is_array($arquivo)) {
            foreach ($arquivo as $lista) {
                if (is_array($lista)) {
                    $Email->addAttachment($lista[1], filename: $lista[0]);
                } elseif (is_string($lista)) {
                    $Email->addAttachment($lista);
                }
            }
        }

        $send = new \SendGrid(env('MAIL_SENDGRID', ''));

        $debug = $this->debug;
        $log = '';
        try {
            $retorno = $send->send($Email);
            if ($debug) {
                $log .= 'Status = ' . $retorno->statusCode() . "\n";
                $log .= 'Header = ' . json_encode($retorno->headers()) . "\n";
                $log .=  'Body = ' . $retorno->body();
            }
        } catch (Exception $e) {
            if ($debug) {
                $log = 'Caught exception: ' . $e->getMessage() . "\n";
            }
        }
        if ($debug) {
            $arquivoLog = fopen(ROOT . '/files/log/sendgrid.txt', 'w+');
            fwrite($arquivoLog, $log);
            fclose($arquivoLog);
        }
    }

    /**
     * Seta o host para enviar o e-mail
     *
     * @param null|string   $host       Host
     * @param null|int      $porta      Porta do host
     * @param null|string   $usuario    Usuário do host
     * @param null|string   $senha      Senha do host
     * @return Self
     */
    public function host(
        ?string $host = null,
        ?int $porta = null,
        ?string $usuario = null,
        ?string $senha = null
    ): self {
        if (!empty($host)) {
            $this->host = $host;
        }
        if (!empty($porta)) {
            $this->porta = $porta;
        }
        if (!empty($usuario)) {
            $this->usuario = $usuario;
        }
        if (!empty($senha)) {
            $this->senha = $senha;
        }
        return $this;
    }

    /**
     * Seta o e-mail que será enviado os e-amils
     *
     * @param string $nome      Nome do usuário do e-mail
     * @param string $email     E-mail que será enviado
     * @return Self
     */
    public function emailEnvio(string $nome, string $email): self
    {
        $this->emailEnvio = [$nome, $email];
        return $this;
    }

    /**
     * Seta o e-mail de resposta
     *
     * @param string $nome      Nome do usuário do e-mail
     * @param string $email     E-mail que será usado para resposta
     * @return Self
     */
    public function emailResposta(string $nome, string $email): self
    {
        $this->emailEnvio = [$nome, $email];
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function validarHost(): bool
    {
        if (empty($this->host)) {
            throw new Excecao(
                'Host é obrigatório!',
                'Você precisa enviar um host para o envio do e-mail.'
            );
        } elseif (empty($this->porta)) {
            throw new Excecao(
                'Porta é obrigatória!',
                'Você precisa enviar a porta do host para o envio do e-mail.'
            );
        } elseif (empty($this->usuario)) {
            throw new Excecao(
                'Usuário é obrigatório!',
                'Você precisa enviar o usuário do host para o envio do e-mail.'
            );
        } elseif (empty($this->senha)) {
            throw new Excecao(
                'Senha é obrigatória!',
                'Você precisa enviar a senha do host para o envio do e-mail.'
            );
        }
        return true;
    }

    private function validarEmailEnvio(): void
    {
        $this->validarEmailGeral($this->emailEnvio, 'envio');
    }

    private function validarEmailResposta(): void
    {
        $this->validarEmailGeral($this->emailResposta, 'resposta');
    }

    private function validarEmailGeral(array $email, string $campo): void
    {
        if (empty($email) || !is_array($email) || count($email) != 2) {
            throw new Excecao(
                'Campo incorreto!',
                'Você precisa enviar um e-mail de ' . $campo . ' com formato correto.'
            );
        } elseif (empty($email[0])) {
            throw new Excecao(
                'Campo incorreto!',
                'Você precisa enviar um nome para o e-mail de ' . $campo . '.'
            );
        } elseif (!$this->validarEmail($email[1])) {
            throw new Excecao(
                'Campo incorreto!',
                'Você precisa enviar um e-mail válido para o e-mail de ' . $campo . '.'
            );
        }
    }

    private function validarEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validarDado(
        string $titulo,
        string $nome,
        string $email,
        string $mensagem,
        string | array $copia,
        string | array $copiaOculta,
        string | array $arquivo
    ): void {
        if (empty($titulo)) {
            throw new Excecao('Erro ao enviar e-mail!', 'É obrigatório enviar um título para o e-mail.');
        } elseif (empty($nome)) {
            throw new Excecao('Erro ao enviar e-mail!', 'É obrigatório enviar o nome do usuário.');
        } elseif (empty($email)) {
            throw new Excecao('Erro ao enviar e-mail!', 'É obrigatório enviar o e-mail do usuário.');
        } elseif (empty($mensagem)) {
            throw new Excecao('Erro ao enviar e-mail!', 'É obrigatório enviar uma mensagem para o e-mail.');
        } elseif (!$this->validarEmailCopia($copia)) {
            throw new Excecao('Erro ao enviar E-mail', 'O campo cópia não está no padrão correto.');
        } elseif (!$this->validarEmailCopia($copiaOculta)) {
            throw new Excecao('Erro ao enviar E-mail', 'O campo cópia oculta não está no padrão correto.');
        } elseif (!$this->validarArquivo($arquivo)) {
            throw new Excecao('Erro ao enviar E-mail', 'O campo cópia oculta não está no padrão correto.');
        }
    }

    private function validarEmailCopia(string | array $email): bool
    {
        if (empty($email)) {
            return true;
        } elseif (is_string($email)) {
            return $this->validarEmail($email);
        } elseif (is_array($email)) {
            foreach ($email as $lista) {
                if (
                    !(is_array($lista) && count($lista) == 2 && !empty($lista[0]) && $this->validarEmail($lista[1])) &&
                    !(is_string($lista) && $this->validarEmail($lista))
                ) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    private function validarArquivo(string | array $arquivo): bool
    {
        if (empty($arquivo)) {
            return true;
        } elseif (is_string($arquivo)) {
            return file_exists($arquivo);
        } elseif (is_array($arquivo)) {
            foreach ($arquivo as $lista) {
                if (
                    !(is_array($lista) && count($lista) == 2 && !empty($lista[0]) && file_exists($lista[1])) &&
                    !(is_string($lista) && file_exists($lista))
                ) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    private function curl(): bool
    {
        try {
            $hash = json_encode($this->listaEmail);

            $LINK = SISTEMA == 'LOCALHOST' ? 'https://' . $_SERVER['SERVER_ADDR'] : LINK;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $LINK . '/__enviar-email-sistema');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'hash' => $hash
            ]);

            if (SISTEMA == 'LOCALHOST') {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            }

            curl_exec($ch);
            curl_close($ch);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
