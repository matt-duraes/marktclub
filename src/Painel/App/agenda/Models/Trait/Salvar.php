<?php

namespace PainelApp\agenda\Models\Trait;

use Helpers\ValidarHelper;

trait Salvar
{

    use Usuario;

    /**
     * Valida os dados enviados no request
     */
    public function validarDadosEnviadosPeloUsuario(): void
    {
        $request = $this->request;
        $Validar = new ValidarHelper();
        $Validar->valor($request->titulo, 'Título')->vazio();

        if (!$this->meuEvento) {
            return;
        }

        $dataInicial = $request->data_inicial;
        $dataFinal = $request->data_final;
        $horaInicial = $request->hora_inicial;
        $horaFinal = $request->hora_final;

        $agora = !empty($horaInicial) ? date('Y-m-d H:i') : date('Y-m-d');

        $Validar
            ->valor($dataInicial, 'Data inicial')->vazio()->data()
            ->valor($dataFinal, 'Data final')->vazio()->data()

            ->valor(
                $dataFinal,
                mensagem: 'A data final do evento deve ser maior ou igual a data de início.'
            )->tamanho('>=', $dataInicial, 'data')

            ->valor($horaInicial)->horaMinuto()
            ->valor($horaFinal)->horaMinuto()

            ->valor(
                $horaFinal,
                mensagem: 'A hora final do evento deve ser maior que a hora de início'
            )->tamanho('>', $horaInicial, 'hora')

            ->valor(
                $dataInicial . ' ' . $horaInicial,
                mensagem: 'Você não pode colocar uma data inferior ou igual a atual.'
            )->tamanho('>', $agora, 'data');

        $convidado = is_array($request->convidado) ? $request->convidado : [];
        foreach ($convidado as $email) {
            $Validar->valor(
                $email,
                mensagem: 'O e-mail "' . $email . '" não está em um formato válido de e-mail para convidado.'
            )->vazio()->email();
        }
    }

    /**
     * Monta o array para salvar/editar evento
     *
     * @return array
     */
    public function montarDadosParaSalvar()
    {
        $request = $this->request;

        $dado = [
            'summary' => $request->titulo,
        ];
        if (!empty($request->local)) {
            $dado['location'] = $request->local;
        }
        if (!empty($request->descricao)) {
            $dado['description'] = $request->descricao;
        }

        if (!$this->meuEvento) {
            return $dado;
        }
        if (!empty($request->hora_inicial)) {
            $dado['start'] = [
                'dateTime' => dataBanco($request->data_inicial) . 'T' . $request->hora_inicial . ':00-03:00',
                'timeZone' => 'America/Sao_Paulo'
            ];
        } else {
            $dado['start']['date'] = dataBanco($request->data_inicial);
        }
        if (!empty($request->hora_final)) {
            $dado['end'] = [
                'dateTime' => dataBanco($request->data_final) . 'T' . $request->hora_final . ':00-03:00',
                'timeZone' => 'America/Sao_Paulo'
            ];
        } else {
            $dado['end']['date'] = dataBanco($request->data_final);
        }
        if ($request->video && !$this->temVideo) {
            $dado['conferenceData'] = [
                'createRequest' => [
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet'
                    ],
                    'requestId' => uuid()
                ]
            ];
        } else if (!$request->video && $this->temVideo) {
            $dado['conferenceData'] = 'None';
        }

        $convidadosAtuais = $this->pegarConvidadosAtuais();
        $euEstouComoConvidado = false;
        if ($request->convidado) {
            foreach ($request->convidado as $email) {
                if ($email == $this->meuEmail()) {
                    $euEstouComoConvidado = true;
                }

                if (array_key_exists($email, $convidadosAtuais)) {
                    $dado['attendees'][] = $convidadosAtuais[$email];
                    continue;
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                $dado['attendees'][] = [
                    'email' => $email,
                    'responseStatus' => 'needsAction'
                ];
            }
        }
        if (!$euEstouComoConvidado) {
            $dado['attendees'][] = [
                'name' => $this->meuNome(),
                'email' => $this->meuEmail(),
                'responseStatus' => 'accepted'
            ];
        }

        return $dado;
    }

    private function pegarConvidadosAtuais(): array
    {
        if (!$this->request->existe('id') || empty($this->request->id)) {
            return [];
        }
        $evento = $this->Cliente->get('/primary/events/' . $this->pegarId($this->request->id))->array();
        if (!array_key_exists('attendees', $evento)) {
            return [];
        }
        $convidado = [];
        foreach ($evento['attendees'] as $item) {
            $convidado[$item['email']] = $item;
        }
        return $convidado;
    }
}
