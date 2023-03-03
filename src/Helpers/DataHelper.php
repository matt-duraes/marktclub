<?php

namespace Helpers;

final class DataHelper
{
    /**
     * @param String $formato Formato que deseja retornar a data
     */
    public function formato(string $formato = 'Y-m-d H:i:s'): string
    {
        if (!$this->validar()) {
            return '';
        }

        $data = $this->retorno;
        return $data->format($formato);
    }

    public function date(): string
    {
        return $this->formato('Y-m-d');
    }

    public function dateTime(): string
    {
        return $this->formato('Y-m-d H:i:s');
    }

    public function data(): string
    {
        return $this->formato('d/m/Y');
    }

    public function dataHora(): string
    {
        return $this->formato('d/m/Y H:i:s');
    }

    public function nomeMes(): string
    {
        if (!$this->validar()) {
            return '';
        }

        $mes = (int)$this->retorno->format('m');

        $lista = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        return $lista[$mes] ?? '';
    }

    public function nomeSemana(): string
    {
        if (!$this->validar()) {
            return '';
        }

        $semana = $this->retorno->format('w');

        $lista = [
            0 => 'Domingo',
            1 => 'Segunda-Feira',
            2 => 'Terça-Feira',
            3 => 'Quarta-Feira',
            4 => 'Quinta-Feira',
            5 => 'Sexta-Feira',
            6 => 'Sábado',
        ];

        return $lista[$semana] ?? '';
    }

    /**
     * @param Bool $hora Se a data deve retornar com H:i:s
     */
    public function extenso(bool $hora = false): string
    {
        if (!$this->validar()) {
            return '';
        }

        $formato = 'Y-m-d';
        if ($hora) {
            $formato .= ' H:i:s';
        }

        $data = $this->retorno->format($formato);

        $explode = explode(' ', $data);
        $explodeData = explode('-', $explode[0]);

        $string = $explodeData[2] . ' de ' . $this->nomeMes() . ' de ' . $explodeData[0];

        if ($hora) {
            $string .= ' às ' . $explode[1] ?? '00:00:00';
        }

        return $string;
    }

    /**
     * Retorna a diferença de dias entre duas datas
     *
     * @param   string      $data       Data que será usada para comparar
     * @return  bool|int                Retorna false se der erro ou intenro com a diferença
     */
    public function diferencaDia(string $data): bool | int
    {
        $comparar = $data;
        if (!$this->validar() || !$this->validarData($comparar)) {
            return false;
        }

        $valor = $this->retorno;
        return $valor->diff(new \DateTime(str_replace('/', '-', $comparar)))->format('%r%a');
    }
    /**
     * Retorna a diferença de horas entre duas datas
     *
     * @param   string      $data       Data que será usada para comparar
     * @return  bool|int                Retorna false se der erro ou intenro com a diferença
     */
    public function diferencaHora(string $data): bool | int
    {
        $comparar = $data;
        if (!$this->validar() || !$this->validarData($comparar)) {
            return false;
        }

        $valor = $this->retorno;
        $diff = $valor->diff(new \DateTime(str_replace('/', '-', $comparar)));
        $dia = abs($diff->format('%R%a')) * 24;
        $resultado = floor($diff->h + $dia);
        return $this->retornarDiferenca($valor, $data, $resultado);
    }
    /**
     * Retorna a diferença de minutos entre duas datas
     *
     * @param   string      $data       Data que será usada para comparar
     * @return  bool|int                Retorna false se der erro ou intenro com a diferença
     */
    public function diferencaMinuto(string $data): bool | int
    {
        $comparar = $data;
        if (!$this->validar() || !$this->validarData($comparar)) {
            return false;
        }

        $valor = $this->retorno;
        $diff = $valor->diff(new \DateTime(str_replace('/', '-', $comparar)));
        $dia = abs($diff->format('%r%a')) * 24 * 60;
        $hora = $diff->h * 60;
        $resultado = floor($diff->i + $dia + $hora);
        return $this->retornarDiferenca($valor, $data, $resultado);
    }
    /**
     * Retorna a diferença de segudoss entre duas datas
     *
     * @param   string      $data       Data que será usada para comparar
     * @return  bool|int                Retorna false se der erro ou intenro com a diferença
     */
    public function diferencaSegundo(string $data): bool | int
    {
        $comparar = $data;
        if (!$this->validar() || !$this->validarData($comparar)) {
            return false;
        }

        $valor = $this->retorno;
        $diff = $valor->diff(new \DateTime(str_replace('/', '-', $comparar)));
        $dia = abs($diff->format('%r%a')) * 24 * 60 * 60;
        $hora = $diff->h * 60 * 60;
        $minuto = $diff->i * 60;
        $resultado = floor($diff->s + $dia + $hora + $minuto);
        return $this->retornarDiferenca($valor, $data, $resultado);
    }
    private function retornarDiferenca($data, $comparacao, $valor)
    {
        $data = $data instanceof \DateTime ? $data : new \DateTime($data);
        $comparacao = $comparacao instanceof \DateTime ? $comparacao : new \DateTime($comparacao);
        return $data <= $comparacao ? $valor : -$valor;
    }

    /**
     * @param Bool $curto True para data com padrão curto ou false para padrão normal
     */
    public function social(bool $curto = false): string
    {
        if (!$this->validar()) {
            return '';
        }
        $valor = $this->retorno;
        if ($valor->format('Y-m-d H:i:s') > date('Y-m-d H:i:s')) {
            return '';
        }
        $data = $valor->diff(new \DateTime(date('Y-m-d H:i:s')));
        if ($curto) {
            return $this->socialCurto($data);
        }
        return $this->socialGrande($data);
    }

    private function socialGrande($data)
    {
        if ($data->y == 1) {
            return 'Há 1 ano';
        } elseif ($data->y > 1) {
            return 'Há ' . $data->y . ' anos';
        } elseif ($data->m == 1) {
            return 'Há 1 mês';
        } elseif ($data->m > 1) {
            return 'Há ' . $data->m . ' meses';
        } elseif ($data->d == 1) {
            return 'Há 1 dia';
        } elseif ($data->d > 1) {
            return 'Há ' . $data->d . ' dias';
        } elseif ($data->h == 1) {
            return 'Há 1 hora';
        } elseif ($data->h > 1) {
            return 'Há ' . $data->h . ' horas';
        } elseif ($data->i == 1) {
            return 'Há 1 minuto';
        } elseif ($data->i > 1) {
            return 'Há ' . $data->i . ' minutos';
        } elseif ($data->s > 10) {
            return 'Há ' . $data->s . ' segundos';
        } else {
            return 'Agora';
        }
    }

    private function socialCurto($data)
    {
        if ($data->y > 0) {
            return $data->y . ' a';
        } elseif ($data->m > 0 || $data->d >= 7) {
            return floor($data->days / 7) . ' sem';
        } elseif ($data->d > 1) {
            return $data->d . ' d';
        } elseif ($data->h > 1) {
            return $data->h . ' h';
        } elseif ($data->i > 1) {
            return $data->i . ' m';
        } elseif ($data->s > 10) {
            return $data->s . ' s';
        } else {
            return 'Agora';
        }
    }

    public function idade()
    {
        if (!$this->validar()) {
            return false;
        }

        return $this->retorno->diff(new \DateTime())->y;
    }

    /**
     * @param Int $numero Número a ser adicionado
     * @param String $tempo Tipo de tempo a ser adicionado, por exemplo, segundos, mimutos, horas, etc
     */
    public function adicionar(int $numero, String $tempo = ''): DataHelper
    {
        if (!$this->validar()) {
            return $this;
        }

        $add = $numero . ' ' . str_replace($this->replaceDataBr, $this->replaceDataEua, $tempo);
        $date = $this->retorno;

        $this->retorno = date_add($date, date_interval_create_from_date_string($add));

        return $this;
    }

    /**
     * @param Int $numero Número a ser removido
     * @param String $tempo Tipo de tempo a ser removido, por exemplo, segundos, mimutos, horas, etc
     */
    public function remover(int $numero, String $tempo = ''): DataHelper
    {
        if (!$this->validar()) {
            return $this;
        }

        $sub = $numero . ' ' . str_replace($this->replaceDataBr, $this->replaceDataEua, $tempo);
        $date = $this->retorno;

        $this->retorno = date_sub($date, date_interval_create_from_date_string($sub));

        return $this;
    }

    /**
     * Pega o último dia do mês
     *
     * @return  self
     */
    public function ultimoDiaMes(): self
    {
        if (!$this->validar()) {
            return $this;
        }

        $data = explode('-', $this->retorno->format('Y-m-d'));
        $this->valor($data[0] . '-' . $data[1] . '-' . cal_days_in_month(CAL_GREGORIAN, $data[1], $data[0]) . ' 23:59:59');

        return $this;
    }
    /**
     * Pega o primeiro dia do mês
     *
     * @return  self
     */
    public function primeiroDiaMes()
    {
        if (!$this->validar()) {
            return $this;
        }

        $data = explode('-', $this->retorno->format('Y-m-d H:i:s'));
        $this->valor($data[0] . '-' . $data[1] . '-01 00:00:00');

        return $this;
    }
    /*
    |--------------------------------------------------------------------------
    | BASE DA CLASSE
    |--------------------------------------------------------------------------
    |
    | Métodos e propriedades base da classe como getters, setters, validações,
    | construtores e demais métodos para o bom funcionamento da classe,
    | não alterar ou remover nenhum dos métodos ou propriedades
    |
     */
    private $retorno;
    private $replaceDataBr;
    private $replaceDataEua = [];

    /**
     * @param String $data Data para ser convertida
     */
    public function __construct(string $data = '')
    {
        $this->replaceDataBr = ['anos', 'ano', 'meses', 'mes', 'semanas', 'semana', 'dias', 'dia', 'horas', 'hora', 'minutos', 'minuto', 'segudos', 'segundo'];
        $this->replaceDataEua = ['year', 'year', 'month', 'month', 'week', 'week', 'day', 'day', 'hour', 'hour', 'minute', 'minute', 'second', 'second'];
        $this->valor($data);
    }

    /**
     * @param   string  $formato    Formato que deseja retornar a data
     * @return  string              Data no formato definido
     */
    public function r(string $formato = 'd/m/Y'): string
    {
        if (!$this->validar()) {
            return '';
        }

        $retorno = $this->retorno;
        $this->retorno = '';

        return $retorno->format($formato);
    }

    /**
     * @param String $data Data para ser convertida
     */
    public function valor(string $data = ''): DataHelper
    {
        if (!$this->validarData($data)) {
            $this->retorno = null;
            return $this;
        }

        $this->retorno = new \DateTime(str_replace('/', '-', $data));
        return $this;
    }

    /**
     * Retorna quantos dias passou da data até hoje
     */
    public function quantosDias()
    {
        if (!$this->validar()) {
            return '';
        }

        $this->valor($this->formato('Y-m-d'));
        $data = $this->retorno->diff(new \DateTime(date('Y-m-d')));

        if ($data->y == 1) {
            return 'Há 1 ano';
        } elseif ($data->y > 1) {
            return 'Há ' . $data->y . ' anos';
        } elseif ($data->m == 1) {
            return 'Há 1 mês';
        } elseif ($data->m > 1) {
            return 'Há ' . $data->m . ' meses';
        } elseif ($data->days == 0) {
            return 'Hoje';
        } elseif ($data->days == 1) {
            return 'Ontem';
        } elseif ($data->days >= 7 && $data->days < 14) {
            return 'Há 1 semana';
        } elseif ($data->days >= 14 && $data->days < 21) {
            return 'Há 2 semanas';
        } elseif ($data->days >= 21 && $data->days < 28) {
            return 'Há 3 semanas';
        } elseif ($data->days >= 28 && $data->days < 31) {
            return 'Há 4 semanas';
        }
        return 'Há ' . $data->days . ' dias';
    }

    private function validarData(string $data): bool
    {
        if (
            empty($data) ||
            in_array($data, ['00/00/0000', '00/00/0000 00:00:00', '0000-00-00', '0000-00-00 00:00:00']) ||
            (!preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/', $data) &&
                !preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data) &&
                !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/", $data) &&
                !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/", $data))
        ) {
            return false;
        }
        return true;
    }

    private function validar(): bool
    {
        if (!$this->retorno instanceof \DateTime) {
            $this->retorno = null;
            return false;
        }

        return true;
    }
}
