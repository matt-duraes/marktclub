<?php

/*/
|--------------------------------------------------------------------------
| CRIA UM CÓDIGO ALEATÓRIO
|--------------------------------------------------------------------------
|
| Gera um código aleatório
|
/*/

use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use Helpers\CryptHelper;

/*/
|--------------------------------------------------------------------------
| CRIA UM UUID
|--------------------------------------------------------------------------
|
| Gera um UUID  versão 4
|
/*/

if (!function_exists('uuid')) {
    function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('jsonDecode')) {
    /**
     * Converter um JSON em array ou objeto
     *
     * @param string    $string     Valor que deseja ser convertido
     * @param bool      $retorno    True para retornar um array ou false para retornar um object
     * @param bool      $array      Se true, em caso de erro, retorna um array vazio
     * @return array|strClass|bool  Array, Object ou false
     */
    function jsonDecode($string, bool $retorno = false, bool $array = false): array|stdClass|bool
    {
        if (is_array($string)) {
            return $string;
        } else if (is_object($string)) {
            $string = json_encode($string);
        } else if (!is_string($string) || empty($string)) {
            return $array ? [] : false;
        }
        $dado = json_decode($string, $retorno);
        if (
            ($retorno && !is_array($dado)) ||
            (!$retorno && !is_array($dado) && !is_object($dado))
        ) {
            return $array ? [] : false;
        }
        return $dado;
    }
}
if (!function_exists('jsonEncode')) {
    /**
     * Converte um valor em JSON
     *
     * @param mixed $valor  Valor que deseja converter
     * @return string   String JSON ou false em caso de erro
     */
    function jsonEncode($valor): string|bool
    {
        return json_encode($valor, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
}

/*/
|--------------------------------------------------------------------------
| PEGA O IP DO USUÁRIO
|--------------------------------------------------------------------------
|
| Tenta pegar o IP do usuário
|
/*/
if (!function_exists('ip')) {
    function ip(): string
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['X-Real-IP']) && !empty($_SERVER['X-Real-IP'])) {
            return $_SERVER['X-Real-IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            return preg_replace('/,.*/', '', $ip);
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return '';
    }
}

/*/
|--------------------------------------------------------------------------
| CRIA UM PASSWORD
|--------------------------------------------------------------------------
|
| Gera um password usando o password_hash
|
/*/
if (!function_exists('password')) {
    /**
     * @param String $password Senha que será criptografada
     * @param String $algoritimo Tipo de algoritimo que será usado para criptografar
     * @param array $option Option para criar a criptografia
     */
    function password(string $password, string $algoritimo = 'PASSWORD_DEFAULT', array $option = ['cost' => 11]): string
    {
        if ($algoritimo == 'PASSWORD_DEFAULT') {
            return password_hash($password, PASSWORD_DEFAULT, $option);
        } elseif ($algoritimo == 'PASSWORD_BCRYPT') {
            return password_hash($password, PASSWORD_BCRYPT, $option);
        } elseif ($algoritimo == 'PASSWORD_ARGON2I') {
            return password_hash($password, PASSWORD_ARGON2I, $option);
        }
    }
}

if (!function_exists('hashIpUser')) {
    /**
     * Criar um hash com o IP e UserAgent do usuário
     *
     * @param string $prefix    Coloca um prefix no inicio do string antes de criptografar
     * @return string String contento o hash MD5
     */
    function hashIpUser(string $prefix = ''): string
    {
        $prefix = !empty($prefix) ? $prefix . '-' : '';
        return md5($prefix . ip() . '_' . $_SERVER['HTTP_USER_AGENT']);
    }
}


if (!function_exists('cookie')) {
    function cookie(
        string $nome,
        $padrao = null
    ) {
        if (array_key_exists($nome, $_COOKIE)) {
            return $_COOKIE[$nome];
        }
        if (is_null($padrao)) {
            mensagemErro('Erro!', 'Não foi possível encontrar o cookie desejado');
        }
        return $padrao;
    }
}

/**
 * Seta um novo Cookie
 *
 * @param string    $nome       Nome do cookie
 * @param mixed     $valor      Valor para o cookie
 * @param int       $dia        Quantidade de dias para o token expirar
 * @param int       $hora       Quantidade de horas para o token expirar
 * @param int       $minuto     Quantidade de minutos para o token expirar
 * @param string    $path       Path do token
 * @param string    $dominio    Domínio do token
 * @return String   Retorna true ou false
 */
if (!function_exists('criarCookie')) {
    function criarCookie(
        string $nome,
        $valor,
        int $dia = 360,
        int $hora = 0,
        int $minuto = 0,
        string $path = '/',
        string $dominio = ''
    ): bool {
        $expirar = mktime(hour: $hora, minute: $minuto, day: $dia);
        return setcookie($nome, $valor, $expirar, $path, $dominio, true, true);
    }
}

/**
 * Deleta o cookie
 *
 * @param string $nome  Nome do cookie para deletar
 */
if (!function_exists('deletarCookie')) {
    function deletarCookie(string $nome): bool
    {
        if (isset($_COOKIE[$nome])) {
            unset($_COOKIE[$nome]);
            return setcookie($nome, '', -1);
        }
        return true;
    }
}

if (!function_exists('inteiro')) {
    /**
     * Gera um número inteiro com o intervalo de min e max
     *
     * @param int $min Valor mínimo
     * @param int $max Valor máximo
     * @return int Número inteiro
     */
    function inteiro(int $min = 1000, int $max = 9999): int
    {
        return rand($min, $max);
    }
}

if (!function_exists('qrcode')) {
    /**
     * Gera um QR Code com o dado enviado e retorna uma imagem com o tamanho definido pela largura e altura
     *
     * @param String $dado Dado que será retornado pelo QR Code
     * @param int $width Largura do QR Code
     * @param int $height Altura do QR Code
     * @return string Link para a imagem do QR Code
     */
    function qrcode(string $dado, int $width = 200, int $height = 200): string
    {
        return 'http://chart.apis.google.com/chart?cht=qr&chl=' . $dado . '&chs=' . $width . 'x' . $height;
    }
}

/*/
|--------------------------------------------------------------------------
| CHAMA PÁGINA DE ERRO
|--------------------------------------------------------------------------
|
| Função para gerar as páginas de erro
|
/*/
if (!function_exists('error404')) {
    function error404(): void
    {
        throw new \Erro\Excecao(status: 404);
    }
}
if (!function_exists('paginaErro')) {
    /**
     * @param Int   $status   Status HTML da página a ser chamada podendo ser: 400, 401, 403, 404, 500
     *                        Caso ao contrário, será 400
     */
    function paginaErro(int $status): void
    {
        throw new \Erro\Excecao(status: in_array($status, [400, 401, 403, 404, 500]) ? $status : 400);
    }
}

/*/
|--------------------------------------------------------------------------
| CRIA VAR_DUMP
|--------------------------------------------------------------------------
|
| Retonar um var_dump do dado enviado e retorna um exit
|
/*/
if (!function_exists('vd')) {
    /**
     * @param mixed $conteudo Conteudo a ser impresso
     */
    function vd($conteudo)
    {
        var_dump($conteudo);
    }
}

/*/
|--------------------------------------------------------------------------
| CRIA VAR_DUMP
|--------------------------------------------------------------------------
|
| Retonar um var_dump do dado enviado e retorna um exit
|
/*/
if (!function_exists('vde')) {
    /**
     * @param mixed $conteudo Conteudo a ser impresso
     */
    function vde($conteudo)
    {
        $header = getallheaders();
        $contentType = array_key_exists('Content-Type', $header) ? explode(';', $header['Content-Type'])[0] : '';
        $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
        $addHtml = $metodo == 'GET' && $contentType != 'application/json';
        if ($addHtml) {
            echo '<html><head><title>VAR_DUMP EXIT</title></head><body>';
        }
        var_dump($conteudo);
        if ($addHtml) {
            echo '</body></html>';
        }
        exit();
    }
}

if (!function_exists('printView')) {
    function printView()
    {
        echo '-->';
    }
}

/*/
|--------------------------------------------------------------------------
| CRIA PRINT_PRE
|--------------------------------------------------------------------------
|
| Imprime um conteúdo e colocar um pre para array e objecto ou
| um br para o restante
|
/*/
if (!function_exists('pp')) {
    /**
     * @param mixed $conteudo Conteudo a ser impresso
     */
    function pp($conteudo, $view = false)
    {
        if ($view) {
            echo '-->';
        }
        if (is_object($conteudo) || is_array($conteudo)) {
            echo '<pre>';
            print_r($conteudo);
        } else {
            echo $conteudo . '<br>' . PHP_EOL;
        }
    }
}

/*/
|--------------------------------------------------------------------------
| CRIA PRINT_PRE_EXIT
|--------------------------------------------------------------------------
|
| Imprime um conteúdo e colocar um pre para array e objecto ou
| um br para o restante. No final, coloca um exit
|
/*/
if (!function_exists('ppe')) {
    /**
     * @param Mixed     $conteudo       Conteudo a ser impresso
     */
    function ppe($conteudo, bool $view = false)
    {
        if ($view) {
            echo '-->';
        }

        $eObjecto = is_object($conteudo) || is_array($conteudo);

        $header = getallheaders();
        $contentType = array_key_exists('Content-Type', $header) ? explode(';', $header['Content-Type'])[0] : '';
        $metodo = $_SERVER['REQUEST_METHOD'] ?? '';

        if ($contentType == 'application/json' && $eObjecto) {
            header('Content-Type: application/json');
            echo json_encode($conteudo);
            exit();
        }

        $addHtml = $metodo == 'GET' && $contentType != 'application/json';
        if ($addHtml) {
            echo '<html><head><title>PRE PRINT EXIT</title></head><body>';
        }

        if ($eObjecto) {
            echo '<pre>';
            print_r($conteudo);
        } else {
            echo $conteudo . PHP_EOL;
        }

        if ($addHtml) {
            echo '</body></html>';
        }
        exit();
    }
}

/*/
|--------------------------------------------------------------------------
| CONVERTE string PARA NUMERO
|--------------------------------------------------------------------------
|
| Remove todos os caracteres que não são números
|
/*/
if (!function_exists('soNumero')) {
    /**
     * @param mixed $string String a ser convertida
     */
    function soNumero(?string $string)
    {
        if (is_null($string)) {
            return '';
        }
        return preg_replace('/[^0-9]/', '', $string);
    }
}

/*/
|--------------------------------------------------------------------------
| ENV
|--------------------------------------------------------------------------
|
| Pega os valores dos arquivos de configuração
|
/*/
if (!function_exists('env')) {
    /**
     * @param String $nome Nome do envia a ser pego
     * @param mixed $padrao Padrão caso não exista o env
     */
    function env(string $nome, $padrao = null)
    {
        $valor = __ENV_USO[$nome] ?? __ENV_PRODUCAO[$nome] ?? $padrao;
        if ($valor == 'true') {
            return true;
        } elseif ($valor == 'false') {
            return false;
        }
        return $valor;
    }
}

/*/
|--------------------------------------------------------------------------
| LOCATION
|--------------------------------------------------------------------------
|
| Faz um location para o link informado
|
/*/
if (!function_exists('location')) {
    /**
     * @param String $link Link para ser redirecionado
     */
    function location(string $link): \Http\Response
    {
        return (new \Http\Response)->location(url: $link);
    }
}

/*/
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
|
| Faz um redirect para o link informado
|
/*/
if (!function_exists('redirect')) {
    /**
     * @param String $link Link para ser redirecionado
     */
    function redirect(string $link): \Http\Response
    {
        return (new \Http\Response)->location(url: $link, status: 301);
    }
}

/*/
|--------------------------------------------------------------------------
| DOWNLAOD
|--------------------------------------------------------------------------
|
| Força o download de um arquivo
|
/*/
if (!function_exists('download')) {
    /**
     * @param String $arquivo Arquivo para ser feito o download
     * @param String $nome Nome do arquivo ao ser feito o download
     * @param string|array $ext Lista de extensões permitidas para fazer downlaod
     */
    function download(string $arquivo): \Http\Response
    {
        return (new \Http\Response)->download(arquivo: $arquivo);
    }
}

/*/
|--------------------------------------------------------------------------
| NULL PARA VAZIO EM ARRAY
|--------------------------------------------------------------------------
|
| Remove os null de um array e troca por string vazia
|
/*/
if (!function_exists('limparNullDeArray')) {
    /**
     * @param array $array Array a ser limpo
     */
    function limparNullDeArray(array $array): array
    {
        $lista = [];
        if (!empty($array)) {
            foreach ($array as $ind => $val) {
                if (is_array($val)) {
                    $lista[$ind] = limparNullDeArray($val);
                } elseif (is_null($val)) {
                    $lista[$ind] = '';
                } else {
                    $lista[$ind] = $val;
                }
            }
        }
        return $lista;
    }
}

/*/
|--------------------------------------------------------------------------
| NULL PARA VAZIO EM ARRAY
|--------------------------------------------------------------------------
|
| Remove os null de um array e troca por string vazia
|
/*/
if (!function_exists('pegarPropriedadeDaEntity')) {
    function pegarPropriedadeDaEntity(
        $Entity,
        ?Request $request = null,
        array $lista = [],
        array $remover = [],
        $null = true,
        $empty = true
    ): array {

        $listaBusca = ['id'];
        if ($request && $request->dado()) {
            $listaBusca = array_merge($listaBusca, array_keys($request->dado()));
        }
        if ($lista) {
            $listaBusca = array_merge($listaBusca, $lista);
        }

        if ($remover) {
            $listaBusca = array_flip($listaBusca);
            foreach ($remover as $item) {
                if (array_key_exists($item, $listaBusca)) {
                    unset($listaBusca[$item]);
                }
            }
            $listaBusca = array_flip($listaBusca);
        }

        $retorno = [];
        foreach ($listaBusca as $ind => $nome) {
            $campo = $nome;
            $nome = is_string($ind) ? $ind : $nome;
            if (!object_key_exists($nome, $Entity)) {
                continue;
            }
            $valor = $Entity->$nome;
            if ($valor instanceof \Status\StatusInterface) {
                $valor = $valor->indice();
            }
            if ($valor instanceof \Modules\Email) {
                $valor = $valor->email();
            } elseif ($valor instanceof \Modules\Data) {
                $valor = $valor->date();
            } elseif ($valor instanceof \Modules\DataHora) {
                $valor = $valor->date();
            } elseif ($valor instanceof \Modules\Nome) {
                $valor = $valor->nome();
            } elseif ($valor instanceof \Modules\Senha) {
                $valor = !empty($valor->senha());
            } elseif ($valor instanceof \Modules\Telefone) {
                $valor = $valor->numero();
            } elseif ($valor instanceof \Modules\Cnpj) {
                $valor = $valor->numero();
            } elseif ($valor instanceof \Modules\Cpf) {
                $valor = $valor->numero();
            } elseif ($valor instanceof \Modules\Genero) {
                $valor = $valor->genero();
            } elseif ($valor instanceof \Modules\EstadoCivil) {
                $valor = $valor->estadoCivil();
            } elseif ($valor instanceof \Modules\EnderecoCep) {
                $valor = $valor->numero();
            } elseif ($valor instanceof \Modules\EnderecoEstado) {
                $valor = $valor->estado();
            } elseif ($valor instanceof \Modules\Dinheiro) {
                $valor = $valor->dinheiro();
            } elseif ($valor instanceof \Modules\Decimal) {
                $valor = $valor->decimal();
            } elseif ($valor instanceof \Modules\Botao) {
                $valor = $valor->valor();
            }
            if (
                (!$null && is_null($valor)) ||
                (!$empty && empty($valor))
            ) {
                continue;
            }
            $retorno[$campo] = $valor;
        }
        return $retorno;
    }
}

/*/
|--------------------------------------------------------------------------
| CONVERTER string PARA ARRAY
|--------------------------------------------------------------------------
|
| Converter string para array usando , para separar o array e : para
| separar o indice e o valor
|
/*/
if (!function_exists('stringArray')) {
    /**
     * @param String $string String a ser convertida em array
     * @param bool $retorno Se true, converte string para array e false para object
     */
    function stringArray(string $string, bool $retorno = true)
    {
        if (strstr($string, ':')) {
            return jsonDecode(str_replace([',""', ':""'], '', preg_replace(['/\t+/', '/\n+/', '/\r+/'], '', '{"' . str_replace(['\\', ':', ','], ['\\\\', '":"', '","'], $string) . '"}')), $retorno);
        } elseif (strstr($string, ',')) {
            return jsonDecode(str_replace(',""', '', preg_replace(['/\t+/', '/\n+/', '/\r+/'], '', '["' . str_replace(['\\', ','], ['\\\\', '","'], $string) . '"]')), $retorno);
        }
        return $string;
    }
}

/*/
|--------------------------------------------------------------------------
| PEGA O HEADER
|--------------------------------------------------------------------------
|
| Pega o header da requisição caso não exista a função nativa do PHP
|
/*/
if (!function_exists('getallheaders')) {
    function getallheaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(mb_strtolower(str_replace('_', ' ', substr($name, 5)), 'UTF-8')))] = $value;
            }
        }
        return $headers;
    }
}
if (!function_exists('hashUnico')) {
    /**
     * Cria um hash único
     *
     * @return string Hash criada
     */
    function hashUnico(?string $prefix = null)
    {
        return md5(uniqid(time()));
    }
}

if (!function_exists('formHash')) {
    /**
     * @param null|string       $indice         Indice para validar o hash, caso seja null, passa um hash md5
     * @param null|string       $id             Prefix do id para os inputs ex.: input_id irá virar input_id_hash e
     *                                          input_id_validacao, caso seja null, não será adicionado ID
     * @param null|string       $validacao      Valor do campo validacao, caso fique em branco, usará um hash aleatória
     */
    function formHash(?string $indice = null, ?string $id = null, ?string $validacao = null): string
    {
        $indice = !empty($indice) ? $indice : md5(uniqid(time()) . '_indice');
        $idHash = !empty($id) ? 'id="' . $id . '_hash"' : '';
        $idValidacao = !empty($id) ? 'id="' . $id . '_validacao"' : '';
        $hash = md5(uniqid(time()));
        $formHash = [
            'id' => uuid(),
            'indice' => $indice,
            'hash' => $hash,
            'data' => strtotime(agora()),
        ];
        $cookie = (new \Helpers\CryptHelper())->encode($formHash);
        $validacao = $validacao === null ? md5($cookie) : $validacao;

        return '
            <input type="hidden" name="form_system_hash" ' . $idHash . ' value="' . md5(ip()) . '.' . $cookie . '">
            <input type="hidden" name="form_system_validacao" ' . $idValidacao . ' value="' . $validacao . '">
        ';
    }
}

if (!function_exists('agora')) {
    /**
     * @param Bool      $br         Se a data vai ser no formato BR
     */
    function agora(bool $br = false): string
    {
        if ($br) {
            return date('d/m/Y H:i:s');
        }
        return date('Y-m-d H:i:s');
    }
}
if (!function_exists('hoje')) {
    /**
     * @param Bool      $br         Se a data vai ser no formato BR
     */
    function hoje(bool $br = false): string
    {
        if ($br) {
            return date('d/m/Y');
        }
        return date('Y-m-d');
    }
}

if (!function_exists('route')) {
    /**
     * @param String $rota Rota que deseja pegar o link
     */
    function route(string $rota): string
    {
        $explode = explode('.', $rota);
        if (count($explode) == 2) {
            $metodo = 'view';
            $controller = $explode[0];
            $action = $explode[1];
        } elseif (count($explode) == 3) {
            $metodo = $explode[0];
            $controller = $explode[1];
            $action = $explode[2];
        } else {
            return '';
        }
        $url = \Route\Route::route($metodo . '.' . $controller . '.' . $action);
        return LINK . $url;
    }
}

if (!function_exists('listarArquivoDiretorio')) {
    /**
     * Lista dos os arquivos de um diretório
     *
     * @param string        $diretorio  Diretório que deseja buscar os arquivos
     * @param string        $inicio     Somente arquivos que começem com o valor informado
     * @param string        $final      Somente arquivos que terminem com o valor informado
     * @param null|array    $ext        Lista de extensões permitidas
     * @return array Array com a lista de arquivos encontrado
     */
    function listarArquivoDiretorio(string $diretorio, string $inicio = '', string $final = '', ?array $ext = null): array
    {
        if (!is_dir($diretorio)) {
            mensagemErro(
                titulo: 'Erro!',
                mensagem: ' O diretório informado não é um diretório válido.',
                status: 403
            );
        }

        $lista = array_diff(scandir($diretorio), ['.', '..']);
        if (!$lista) {
            return [];
        }

        $array = [];
        $regInicio = '/^' . $inicio . '/';
        $regFinal = '/' . $final . '$/';

        foreach ($lista as $arquivo) {
            $arquivoExt = pathinfo($arquivo, PATHINFO_EXTENSION);
            $arquivoNome = pathinfo($arquivo, PATHINFO_FILENAME);
            if (
                ($ext && !in_array($arquivoExt, $ext)) ||
                (!empty($inicio) && !preg_match($regInicio, $arquivoNome)) ||
                (!empty($final) && !preg_match($regFinal, $arquivoNome))
            ) {
                continue;
            }
            $array[] = $arquivo;
        }
        return $array;
    }
}
if (!function_exists('arquivoExt')) {
    function arquivoExt(string $arquivo)
    {
        return pathinfo($arquivo, PATHINFO_EXTENSION);
    }
}
if (!function_exists('arquivoNome')) {
    function arquivoNome(string $arquivo)
    {
        return pathinfo($arquivo, PATHINFO_FILENAME);
    }
}
if (!function_exists('arquivoNomeExt')) {
    function arquivoNomeExt(string $arquivo)
    {
        return pathinfo($arquivo, PATHINFO_FILENAME) . '.' . pathinfo($arquivo, PATHINFO_EXTENSION);
    }
}
if (!function_exists('arquivoDiretorio')) {
    function arquivoDiretorio(string $arquivo)
    {
        return pathinfo($arquivo, PATHINFO_DIRNAME);
    }
}
if (!function_exists('arquivoTamanho')) {
    /**
     * Retorna o tamanho do arquivo em MB
     *
     * @param string $path Path do arquivo
     */
    function arquivoTamanho(string $path)
    {
        return round((filesize($path) / 1000) / 1000, 2);
    }
}
if (!function_exists('pegarHtml')) {
    /**
     * @param String    $arquivo        Arquivo que deseja pegar dentro de /html/views
     *                                  Caso não passar o arquivo.php, ele irá assumir que será index.php
     * @param Array     $var            Lista de variáveis que serão enviadas ao arquivo em formato de array
     */
    function pegarHtml(string $arquivo, array $var = [])
    {
        $arquivo = preg_replace(['/\./', '/^\//', '/\/php$/'], ['/', '', '.php'], $arquivo);
        if (!preg_match('/\.php$/', $arquivo)) {
            $arquivo .= '/index.php';
        }
        if (!file_exists(ROOT . '/files/php/views/' . $arquivo)) {
            throw new Erro(mensagem: 'O arquivo ' . $arquivo . ' não existe.');
        }
        extract($var, EXTR_OVERWRITE);

        ob_start();
        include ROOT . '/files/php/views/' . $arquivo;
        return ob_get_clean();
    }
}
if (!function_exists('pegarHtmlEmail')) {
    /**
     * @param String            $tipo           O Tipo de e-mail tendo o padrão de sistema como botao, numero e mensagem
     * @param String            $titulo         Título do e-mail
     * @param String            $assinto        Assunto do e-mail
     * @param String            $mensagem       Mensagem que será enviada para o usuário
     * @param null|string       $link           Link que o usuário será enviado
     * @param null|string       $botao          Texto que irá dentro do botão do link
     * @param null|string       $acao           Ação do porque o usuário está recebendo esse e-mail, ex: ... recebemos uma solicitação de {{$acao}} para sua conta ...
     * @param null|string       $acaoTexto      Texto completo do porque o usuário está recebendo esse e-mail. Colocar {{PADRAO}} no final caso queira usar o final padrão do texto de ação.
     * @param null|string       $idPublico      ID público do usuário para remover o e-mail dele da lista de disparo
     * @param Null|Array        $var            Array que será convertido para variáveis caso precise de mais variáveis fora as padrões
     */
    function pegarHtmlEmail(
        string $tipo,
        string $titulo,
        string $assunto,
        string $mensagem,
        ?string $link = null,
        ?string $botao = null,
        ?string $acao = null,
        ?string $acaoTexto = null,
        ?string $idPublico = null,
        ?array $var = []
    ) {
        if (!file_exists(ROOT . '/files/php/views/api/email/' . $tipo . '/index.php')) {
            throw new Erro(mensagem: 'O tipo ' . $tipo . ' não contem um html de e-mail padrão.');
        }

        if ($var) {
            extract($val, EXTR_OVERWRITE);
        }

        $LINK = LINK;
        $LINK_API = LINK_API;
        $LINK_SITE = LINK_SITE;
        $HOST = str_replace(['https://', 'http://'], '', $LINK_SITE);
        $data = date('d/m/Y H:i:s');
        $ip = ip();
        $acaoTexto = str_replace(
            '{{PADRAO}}',
            'Caso não tenha feito essa ação, exclua este email. Se achou essa ação suspeira, verifique sua conta.',
            $acaoTexto
        );

        $browser = LINK . '/email/browser/' . (new CryptHelper(url: true))->encode([
            'tipo' => $tipo,
            'titulo' => $titulo,
            'assunto' => $assunto,
            'mensagem' => $mensagem,
            'link' => $link,
            'botao' => $botao,
            'acao' => $acao,
            'acaoTexto' => $acaoTexto,
            'idPublico' => $idPublico,
            'var' => $var,
            'data' => $data,
            'ip' => $ip,
            'LINK' => $LINK,
            'LINK_API' => $LINK_API,
            'LINK_SITE' => $LINK_SITE,
            'HOST' => $HOST,
        ], 'hash_email_geral');

        ob_start();
        include ROOT . '/files/php/views/api/email/' . $tipo . '/index.php';
        return ob_get_clean();
    }
}
if (!function_exists('vazio')) {
    function vazio($item)
    {
        if (is_object($item)) {
            return count(get_object_vars($item)) == 0;
        }
        return empty($item);
    }
}
if (!function_exists('object_key_exists')) {
    /**
     * Verifica se existe uma chave no objeto
     *
     * @param string    $chave      Chave que deseja procurar
     * @param mixed     $objeto     Objeto que deseja validar
     * @return bool
     */
    function object_key_exists(string $chave, $objeto)
    {
        $array = get_object_vars($objeto);
        return array_key_exists($chave, $array);
    }
}
if (!function_exists('descriptografarDado')) {
    /**
     * Criptografa um array de dados ou uma string
     *
     * @param   string|array    $valor  String com valor a criptografar ou um array ou uma lista de array
     * @param   array           $lista  Lista de campos que devem ser criptografados quando o valor for um array
     * @return  string|array            String quando o valor for uma string ou um array quando o valor for um array
     */
    function descriptografarDado(string|stdClass|array $valor, array $lista = [], ?string $chave = null): array|string
    {
        $chave =
            is_null($chave) && defined('TOKEN') && array_key_exists('app', TOKEN) ?
            TOKEN['app']->chave_privada :
            $chave;
        $Crypt = new CryptHelper(chavePrivada: $chave);

        if ($valor instanceof stdClass) {
            $valor = (array) $valor;
        } else if (!is_array($valor)) {
            return !empty($valor) ? $Crypt->decode($valor) : $valor;
        }

        $retorno = [];
        foreach ($valor as $ind => $val) {
            if (is_array($val) || is_object($val)) {
                foreach ($val as $ind2 => $val2) {
                    if (!empty($val2) && in_array($ind2, $lista)) {
                        $valorTemp = $Crypt->decode($val2);
                        if (!empty($val2) && empty($valorTemp)) {
                            mensagemErro(
                                'Erro!',
                                'Não foi possível remover a criptografia do indice ' . $ind2 . ' ou ele não está criptografado.'
                            );
                        }
                        $val2 = $valorTemp;
                    }
                    $retorno[$ind2] = $val2;
                }
                continue;
            }
            if (!empty($val) && in_array($ind, $lista)) {
                $valorTemp = $Crypt->decode($val);
                if (!empty($val) && empty($valorTemp)) {
                    mensagemErro(
                        'Erro!',
                        'Não foi possível remover a criptografia do indice ' . $ind . ' ou ele não está criptografado.'
                    );
                }
                $val = $valorTemp;
            }
            $retorno[$ind] = $val;
        }
        return $retorno;
    }
}
if (!function_exists('criptografarDado')) {
    /**
     * Criptografa um array de dados ou uma string
     *
     * @param   string|array    $valor  String com valor a criptografar ou um array ou uma lista de array
     * @param   array           $lista  Lista de campos que devem ser criptografados quando o valor for um array
     * @return  string|array            String quando o valor for uma string ou um array quando o valor for um array
     */
    function criptografarDado(string|stdClass|array $valor, array $lista = [], ?string $chave = null): array|string
    {
        $chave =
            is_null($chave) && defined('TOKEN') && array_key_exists('app', TOKEN) ?
            TOKEN['app']->chave_publica :
            $chave;

        $Crypt = new CryptHelper(chavePublica: $chave);

        if ($valor instanceof stdClass) {
            $valor = (array) $valor;
        }

        if (!is_array($valor)) {
            return !empty($valor) ? $Crypt->encode($valor) : '';
        }

        $retorno = [];
        foreach ($valor as $ind => $val) {
            if ((is_array($val) || is_object($val)) && !vazio($val)) {
                foreach ($val as $ind2 => $val2) {
                    if (!empty($val2) && in_array($ind2, $lista)) {
                        $val2 = $Crypt->encode($val2);
                    }
                    $retorno[$ind][$ind2] = !empty($val2) ? $val2 : '';
                }
                continue;
            } else if (is_array($val) || is_object($val)) {
                $retorno[$ind] = $val;
                continue;
            } else if (!empty($val) && in_array($ind, $lista)) {
                $val = $Crypt->encode($val);
            }
            $retorno[$ind] = !empty($val) ? $val : '';
        }
        return $retorno;
    }
}
if (!function_exists('base64Encode')) {
    /**
     * @param string|array      $dado       Dado a ser criptografado
     * @param null|string       $url        Se deve converter a hash para URL
     * @return string                       Criptografia gerada
     */
    function base64Encode(string | array $dado, bool $url = false)
    {
        return (new \Helpers\CryptHelper(url: $url))->encode($dado);
    }
}
if (!function_exists('base64Decode')) {
    /**
     * @param string            $hash       Hash que deseja descriptografar
     * @return string|array                 Conteúdo descriptografado
     */
    function base64Decode(?string $hash): array | string | bool
    {
        if (!is_string($hash)) {
            return false;
        }
        return (new \Helpers\CryptHelper())->decode($hash);
    }
}
if (!function_exists('arrayString')) {
    /**
     * Converte um Array para o padrão de string do sistema 1=valor 01|2=valor 02
     * @param Array $array      Array a ser convertido
     */
    function arrayString(array $array)
    {
        $retorno = [];
        foreach ($array as $id => $valor) {
            $retorno[] = $id . '=' . preg_replace('/[^a-zA-Zà-úÀ-Ú0-9\ ]/', '', $valor);
        }
        return implode('|', $retorno);
    }
}
if (!function_exists('stringArray')) {
    /**
     * Converte uma string de array para um arrray PHP
     * @param String $string      String a ser convertido
     */
    function arrayString(string $string)
    {
        if (!str_contains($string, '|')) {
            return [];
        }

        $retorno = [];
        foreach (explode('|', $string) as $val) {
            $explode = explode('=', $val);
            if (count($explode) == 2) {
                $retorno[$explode[0]] = $explode[1];
                continue;
            }
            $retorno[] = $explode[0];
        }
        return $retorno;
    }
}
if (!function_exists('inKey')) {
    /**
     * @param string|array      $lista      String ou array com a lista de item a validar. Ex.: data->br
     * @param Array|StdClass    $item       Item que deseja validar
     */
    function inKey(string|array $lista, $item)
    {
        if ((!is_array($item) && !is_object($item)) || vazio($item)) {
            return false;
        }
        if (is_string($lista)) {
            $lista = [$lista];
        }
        foreach ($lista as $linha) {
            if (
                !str_contains($linha, '->') &&
                (
                    (is_array($item) && !array_key_exists($linha, $item)) ||
                    (is_object($item) && !isset($item->$linha)))
            ) {
                return false;
            }
            $explode = explode('->', $linha);
            $quantidade = count($explode);
            $itemTemp = $item;
            for ($i = 0; $i < $quantidade; ++$i) {
                $valorTemp = $explode[$i];
                if (
                    (is_array($itemTemp) && !array_key_exists($valorTemp, $itemTemp)) ||
                    (is_object($itemTemp) && !isset($itemTemp->$valorTemp))
                ) {
                    return false;
                }
                $itemTemp = is_array($itemTemp) ? $itemTemp[$valorTemp] : $itemTemp->$valorTemp;
            }
        }
        return true;
    }
}

if (!function_exists('sessao')) {
    /**
     * Seta ou pega uma sessão
     *
     * @param null|string|array     $indice     Indice da sessão podendo usar . para pegar mais de um nível (20 no máximo) ou array para setar varios valores
     * @param midex                 $valor      Valor para a sessão
     * @param midex                 $padrao     Valor padrão caso não exista a sessão
     * @param bool                  $flash      Se a sessão vai ser permanente ou se vai ser excluida depois de uso ou reload
     * @return mixed
     */
    function sessao(null|string|array $indice = null, $valor = null, $padrao = null)
    {
        $SESSAO = new Symfony\Component\HttpFoundation\Session\Session();
        $SESSAO->registerBag((new \System\Config\Session(true))->storage());

        if (is_array($indice)) {
            foreach ($indice as $propriedade => $resultado) {
                $SESSAO->set($propriedade, $resultado);
            }
            return;
        } else if (empty($indice)) {
            return $SESSAO->all();
        }

        $objeto = [];
        if (is_string($indice) && str_contains($indice, '.')) {
            $objeto = explode('.', $indice);
            $indice = $objeto[0];
            unset($objeto[0]);
        }

        $sessaoExiste = $SESSAO->has($indice);
        if (is_null($valor) && !$sessaoExiste && is_null($padrao)) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro no sistema, por favor, recarregue a página e tente novamente.',
                localhost: 'A sessão ' . $indice . ' não existe.'
            );
        } else if (is_null($valor) && !$sessaoExiste) {
            return $padrao;
        } else if (is_null($valor) && empty($objeto)) {
            return $SESSAO->get($indice);
        } else if (!is_null($valor) && empty($objeto)) {
            $SESSAO->set($indice, $valor);
            return;
        } else if (is_null($valor) && !empty($objeto)) {
            $valorAtual = $SESSAO->get($indice);
            foreach ($objeto as $subIndice) {
                $existe = is_array($valorAtual) && array_key_exists($subIndice, $valorAtual);
                if (!$existe && is_null($padrao)) {
                    throw new Excecao(
                        'Erro!',
                        SISTEMA == 'LOCALHOST' ?
                            'O indice ' . $subIndice . ' não existe na sessão.' :
                            'Ocorreu um erro no sistema, por favor, recarregue a página e tente novamente.'
                    );
                } else if (!$existe) {
                    return $padrao;
                }
                $valorAtual = $valorAtual[$subIndice];
            }
            return $valorAtual;
        } else if (!is_null($valor) && !empty($objeto)) {
            $quantidade = count($objeto);
            $valorAtual = $SESSAO->get($indice);
            if ($quantidade == 1) {
                $valorAtual[$objeto[1]] = $valor;
            } else if ($quantidade == 2) {
                $valorAtual[$objeto[1]][$objeto[2]] = $valor;
            } else if ($quantidade == 3) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]] = $valor;
            } else if ($quantidade == 4) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]] = $valor;
            } else if ($quantidade == 5) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]] = $valor;
            } else if ($quantidade == 6) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]] = $valor;
            } else if ($quantidade == 7) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]] = $valor;
            } else if ($quantidade == 8) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]] = $valor;
            } else if ($quantidade == 9) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]] = $valor;
            } else if ($quantidade == 10) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]] = $valor;
            } else if ($quantidade == 11) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]] = $valor;
            } else if ($quantidade == 12) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]] = $valor;
            } else if ($quantidade == 13) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]] = $valor;
            } else if ($quantidade == 14) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]] = $valor;
            } else if ($quantidade == 15) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]] = $valor;
            } else if ($quantidade == 16) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]] = $valor;
            } else if ($quantidade == 17) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]] = $valor;
            } else if ($quantidade == 18) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]] = $valor;
            } else if ($quantidade == 19) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]][$objeto[19]] = $valor;
            } else if ($quantidade == 20) {
                $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]][$objeto[19]][$objeto[20]] = $valor;
            } else {
                throw new Excecao(
                    titulo: 'Erro',
                    mensagem: SISTEMA == 'LOCALHOST' ?
                        'Você só pode usar no máximo 20 níveis.' :
                        'Ocorreu um erro no sistema, por favor, recarregue a página e tente novamente.'
                );
            }
            $SESSAO->set($indice, $valorAtual);
        }
    }
}
if (!function_exists('sessaoFlash')) {
    /**
     * Seta ou pega uma sessão flash
     *
     * @param string     $indice     Indice da sessão
     * @param string     $valor      Valor para a sessão
     * @param string     $padrao     Valor padrão caso não exista a sessão
     */
    function sessaoFlash(string $indice = null, ?string $valor = null, ?string $padrao = null)
    {
        if (empty($indice)) {
            return;
        }
        $SESSAO = new Symfony\Component\HttpFoundation\Session\Session;
        $SESSAO->registerBag((new \System\Config\Session(true))->storage());

        if (!empty($valor)) {
            $SESSAO->getFlashBag()->add($indice, $valor);
            return;
        }
        $retorno = $SESSAO->getFlashBag()->get($indice);
        return empty($retorno) ? $padrao : $retorno;
    }
}
if (!function_exists('sessaoDeletar')) {
    /**
     * Deleta um indice da sessão
     * @param null|string|array     $indice     Indice da sessão podendo usar . para pegar mais de um nível (20 no máximo) ou array para deletar varios valores. Caso não passe um indice, será destruido todos os indices
     */
    function sessaoDeletar(null|string|array $indice = null): void
    {
        $SESSAO = new Symfony\Component\HttpFoundation\Session\Session;
        $SESSAO->registerBag((new \System\Config\Session(true))->storage());
        if (is_array($indice)) {
            foreach ($indice as $subIndice) {
                $SESSAO->remove($subIndice);
            }
            return;
        } else if (empty($indice)) {
            $SESSAO->clear();
            return;
        }

        $objeto = [];
        if (is_string($indice) && str_contains($indice, '.')) {
            $objeto = explode('.', $indice);
            $indice = $objeto[0];
            unset($objeto[0]);
        }

        if (empty($objeto)) {
            $SESSAO->remove($indice);
            return;
        }

        $valor = '';
        $valorAtual = $SESSAO->get($indice);
        $quantidade = count($objeto);
        if ($quantidade == 1) {
            unset($valorAtual[$objeto[1]]);
        } else if ($quantidade == 2) {
            unset($valorAtual[$objeto[1]][$objeto[2]]);
        } else if ($quantidade == 3) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]] = $valor;
        } else if ($quantidade == 4) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]] = $valor;
        } else if ($quantidade == 5) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]] = $valor;
        } else if ($quantidade == 6) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]] = $valor;
        } else if ($quantidade == 7) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]] = $valor;
        } else if ($quantidade == 8) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]] = $valor;
        } else if ($quantidade == 9) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]] = $valor;
        } else if ($quantidade == 10) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]] = $valor;
        } else if ($quantidade == 11) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]] = $valor;
        } else if ($quantidade == 12) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]] = $valor;
        } else if ($quantidade == 13) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]] = $valor;
        } else if ($quantidade == 14) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]] = $valor;
        } else if ($quantidade == 15) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]] = $valor;
        } else if ($quantidade == 16) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]] = $valor;
        } else if ($quantidade == 17) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]] = $valor;
        } else if ($quantidade == 18) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]] = $valor;
        } else if ($quantidade == 19) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]][$objeto[19]] = $valor;
        } else if ($quantidade == 20) {
            $valorAtual[$objeto[1]][$objeto[2]][$objeto[3]][$objeto[4]][$objeto[5]][$objeto[6]][$objeto[7]][$objeto[8]][$objeto[9]][$objeto[10]][$objeto[11]][$objeto[12]][$objeto[13]][$objeto[14]][$objeto[15]][$objeto[16]][$objeto[17]][$objeto[18]][$objeto[19]][$objeto[20]] = $valor;
        } else {
            throw new Excecao(
                titulo: 'Erro',
                mensagem: SISTEMA == 'LOCALHOST' ?
                    'Você só pode usar no máximo 20 níveis.' :
                    'Ocorreu um erro no sistema, por favor, recarregue a página e tente novamente.'
            );
        }
        $SESSAO->set($indice, $valorAtual);
    }
}
if (!function_exists('sessaoExiste')) {
    /**
     * Verifica se uma sessão existe
     * @param string|array $indice      Indice da sessão podendo usar . para pegar mais de um nível ou array para várias
     * @return bool                     True para se a sessão existir ou false
     */
    function sessaoExiste(string|array $indice): bool
    {
        $SESSAO = new Symfony\Component\HttpFoundation\Session\Session;
        $SESSAO->registerBag((new \System\Config\Session(true))->storage());

        if (is_array($indice)) {
            foreach ($indice as $subIndice) {
                if (!$SESSAO->has($subIndice)) {
                    return false;
                }
            }
            return true;
        }

        $objeto = [];
        if (str_contains($indice, '.')) {
            $objeto = explode('.', $indice);
            $indice = $objeto[0];
            unset($objeto[0]);
        }
        if (empty($objeto)) {
            return $SESSAO->has($indice);
        }

        $valorAtual = $SESSAO->get($indice);
        foreach ($objeto as $subIndice) {
            if (is_array($valorAtual) && array_key_exists($subIndice, $valorAtual)) {
                $valorAtual = $valorAtual[$subIndice];
                continue;
            }
            return false;
        }
        return true;
    }
}
if (!function_exists('sessaoDestroi')) {
    /**
     * Destroi a sessão atual e reseta a sessao_id
     */
    function sessaoDestruir(): bool
    {
        $SESSAO = new Symfony\Component\HttpFoundation\Session\Session;
        return $SESSAO->invalidate();
    }
}

if (!function_exists('montarSelect')) {
    /**
     * Conta um array para usar como select no padrão indice => valor
     *
     * @param array         $lista      Array com uma lista de itens para ser adicionado no select
     * @param null|string   $titulo     Valor inicial e vazio do select. Ex <option value="">Valor do titulo</option>
     * @param null|string   $indice     Qual campo da $lista será o indice
     * @param null|string   $valor      Qual campo da $lista será o valor
     * @return array
     */
    function montarSelect(array $lista, ?string $titulo = null, ?string $indice = null, ?string $valor = null): array
    {
        $select = [];
        if (!empty($titulo)) {
            $select[''] = $titulo;
        }

        if (!empty($indice) && !empty($valor)) {
            foreach ($lista as $r) {
                $select[$r->$indice] = $r->$valor;
            }
            return $select;
        }
        foreach ($lista as $ind => $val) {
            $select[$ind] = $val;
        }
        return $select;
    }
}

if (!function_exists('nomeUnico')) {
    /**
     * Gera uma hash md5 para usar como nome único
     *
     * @return string   Hash gerada
     */
    function nomeUnico(): string
    {
        return md5(uniqid(time()));
    }
}
if (!function_exists('echoView')) {
    /**
     * Escapa os valores para impressão
     *
     * @param mixed $valor  Valor a ser escapado
     * @return string       Valor escapada
     */
    function echoView($valor): string
    {
        return !empty($valor) ? htmlentities(string: $valor, flags: ENT_QUOTES, encoding: 'UTF-8') : '';
    }
}

if (!function_exists('object')) {
    /**
     * Concerte um Array em Object
     *
     * @param array $array      Array que deseja converter
     * @return stdClass
     */
    function object(array $array): stdClass
    {
        $object = json_decode(json_encode($array), false);
        return is_object($object) ? $object : (object)[];
    }
}
if (!function_exists('caixaCodigo')) {
    /**
     * Gera uma caixa de código, para funcinar deve importar o arquivo Caixa no JS e CSS
     *
     * @param string        $codigo     Código que deve ser usado
     * @param null|string   $arquivo    Nome do arquivo
     * @param null|string   $linguagem  A linguagem que será usada podendo ser: js, css, stylus, php, dart, dockerfile, shell, json, plaintext, swift, yaml, xml, markdown, sql ou typescript
     */
    function caixaCodigo(string $codigo, ?string $arquivo = null, ?string $linguagem = null)
    {
        $arquivoHtml = !empty($arquivo) ? '<div class="fw_codigo_arquivo">' . $arquivo . '</div>' : '';
        if (!empty($arquivo) && empty($linguagem)) {
            $linguagem = arquivoExt($arquivo);
        }
        $linguagem = $linguagem == 'html' ? 'xml' : $linguagem;
        $linguagem = $linguagem == 'styl' ? 'stylus' : $linguagem;
        $linguagem = $linguagem == 'ts' ? 'typescript' : $linguagem;

        $classe = !empty($linguagem) && in_array($linguagem, [
            'js', 'css', 'stylus', 'php', 'dart', 'dockerfile', 'shell', 'json', 'plaintext',
            'swift', 'yaml', 'xml', 'markdown', 'sql', 'typescript'
        ]) ? 'language-' . $linguagem : '';

        return '<div class="fw_caixa_codigo">' . $arquivoHtml . '<pre><code class="' . $classe . '">' . trim($codigo) . '</code></pre></div>';
    }
}

if (!function_exists('imagemUsuario')) {
    /**
     * Pega a imagem do usuário
     *
     * @param   null|string $tipo       Tipo de imagem
     * @param   null|string $arquivo    Arquivo de imagem
     * @param   null|string $facebook   URL da imagem do Facebook
     * @param   null|string $google     URL da imagem do Google
     * @return  string                  URL da imagem
     */
    function imagemUsuario(?string $tipo = null, ?string $arquivo = null, ?string $facebook = null, ?string $google = null): string
    {
        if ($tipo == 1 && !empty($facebook)) {
            return $facebook;
        } else if ($tipo == 2 && !empty($google)) {
            return $google;
        }
        if (!empty($arquivo)) {
            return arquivoPrivado($arquivo);
        }
        return arquivoPublico('usuario', 'padrao.png');
    }
}
if (!function_exists('arquivoPublico')) {
    /**
     * Gera um link para um arquivo público
     *
     * @param   string      $diretorio  Diretório que o arquivo pertence
     * @param   string      $arquivo    Arquivo que deseja pegar
     * @param   array       $parametro  Parametro para inserir como GET na URL
     * @param   string      $padrao     Imagem padrão caso não tenha arquivo
     * @return  string                  Url do arquivo
     */
    function arquivoPublico(string $diretorio, string $arquivo, array $parametro = [], string $padrao = '')
    {
        if (empty($arquivo)) {
            return $padrao;
        }
        $query = [];
        foreach ($parametro as $ind => $val) {
            $query[] = [$ind . '=' . $val];
        }
        $query = !empty($query) ? '?' . implode('&', $query) : '';
        $diretorio = preg_replace('/\/$/', '', $diretorio);

        if (!file_exists(DIRETORIO_PUBLICO . '/' . $diretorio . '/' . $arquivo)) {
            return '';
        }

        $cifra = 'AES-256-CBC';
        $iv = strCortar('d750d28036f7447ffe8e0d2ac2d2069b', openssl_cipher_iv_length($cifra), '', true);
        $chave = '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';
        $hash = openssl_encrypt($diretorio . '/' . $arquivo, $cifra, $chave, 0, $iv);

        return LINK_ARQUIVO_PUBLICO . '/aqioulc.' . str_replace(['+', '/', '='], ['-', '_', ':'], $hash) . $query;
    }
}
if (!function_exists('arquivoPublicoNome')) {
    /**
     * Pega o nome de um arquivo público
     *
     * @param   string    $link     Link do arquivo público
     * @return  string              Diretório e nome do arquivo
     */
    function arquivoPublicoNome(string $link)
    {
        $cifra = 'AES-256-CBC';
        $chave = '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';

        $hash = explode('aqioulc.', $link)[1] ?? '';
        $hash = str_replace(['-', '_', ':'], ['+', '/', '='], $hash);
        $iv = strCortar('d750d28036f7447ffe8e0d2ac2d2069b', openssl_cipher_iv_length($cifra), '', true);

        try {
            return openssl_decrypt(
                data: $hash,
                cipher_algo: $cifra,
                passphrase: $chave,
                options: 0,
                iv: $iv
            );
        } catch (\Throwable) {
            return '';
        }
    }
}
if (!function_exists('arquivoPrivado')) {
    /**
     * Gera um link para um arquivo privado
     *
     * @param   null|string     $id         ID do arquivo no banco (uuid)
     * @param   array           $parametro  Parametro para inserir como GET na URL
     * @param   string          $padrao     Arquivo padrão caso não tenha ID
     * @return  string                      Url do arquivo
     */
    function arquivoPrivado(?string $id, array $parametro = [], string $padrao = '')
    {
        if (empty($id)) {
            return $padrao;
        }

        $query = [];
        foreach ($parametro as $ind => $val) {
            $query[] = [$ind . '=' . $val];
        }
        $query = !empty($query) ? '?' . implode('&', $query) : '';

        $cifra = 'AES-256-CBC';
        $iv = strCortar('d750d28036f7447ffe8e0d2ac2d2069b', openssl_cipher_iv_length($cifra), '', true);
        $chave = '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';
        $hash = openssl_encrypt($id, $cifra, $chave, 0, $iv);

        return LINK_ARQUIVO_PRIVADO . '/aqiorvd.' . str_replace(['+', '/', '='], ['-', '_', ':'], $hash) . $query;
    }
}
if (!function_exists('arquivoPrivadoId')) {
    /**
     * Pega o ID de um link de arquivo privado
     *
     * @param string    $link       Link do arquivo que deseja pegar o ID
     * @return string               ID do arquivo
     */
    function arquivoPrivadoId(string $link)
    {
        $cifra = 'AES-256-CBC';
        $chave = '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';

        $hash = explode('aqiorvd.', $link)[1] ?? '';
        $hash = str_replace(['-', '_', ':'], ['+', '/', '='], $hash);
        $iv = strCortar('d750d28036f7447ffe8e0d2ac2d2069b', openssl_cipher_iv_length($cifra), '', true);

        try {
            return openssl_decrypt(
                data: $hash,
                cipher_algo: $cifra,
                passphrase: $chave,
                options: 0,
                iv: $iv
            );
        } catch (\Throwable) {
            return '';
        }
    }
}

if (!function_exists('removerIndiceVazio')) {
    /**
     * Retorna um array apenas com os indices que tenham conteúdo
     *
     * @param array $array Array que deseja limpar
     * @return array
     */
    function removerIndiceVazio(array $array): array
    {
        $retorno = [];
        foreach ($array as $ind => $val) {
            $valor = trim($val);
            if (!empty($valor)) {
                $retorno[$ind] = $valor;
            }
        }
        return $retorno;
    }
}
if (!function_exists('eLocalhost')) {
    /**
     * Verifica se o sistema está em localhost
     *
     * @return bool
     */
    function eLocalhost(): bool
    {
        return SISTEMA == 'LOCALHOT';
    }
}
if (!function_exists('eHomologacao')) {
    /**
     * Verifica se o sistema está em homologacao
     *
     * @return bool
     */
    function eHomologacao(): bool
    {
        return SISTEMA == 'HOMOLOGACAO';
    }
}
if (!function_exists('eProducao')) {
    /**
     * Verifica se o sistema está em producao
     *
     * @return bool
     */
    function eProducao(): bool
    {
        return SISTEMA == 'PRODUCAO';
    }
}
if (!function_exists('naoProducao')) {
    /**
     * Verifica se o sistema não está em producao
     *
     * @return bool
     */
    function naoProducao(): bool
    {
        return SISTEMA != 'PRODUCAO';
    }
}
if (!function_exists('naoLocalhost')) {
    /**
     * Verifica se o sistema não está em localhost
     *
     * @return bool
     */
    function naoLocalhost(): bool
    {
        return SISTEMA != 'LOCALHOST';
    }
}
