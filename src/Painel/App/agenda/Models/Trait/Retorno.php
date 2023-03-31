<?php

namespace PainelApp\agenda\Models\Trait;

use Helpers\DataHelper;

trait Retorno
{
    use Usuario;
    use Id;

    /**
     * Monta os dados de retorno do evento
     *
     * @param array $evento A lista de eventos retornada pelo Google
     * @return array
     */
    public function pegarRetorno(array $evento): array
    {
        $retorno = [];
        $Data = new DataHelper();

        foreach ($evento as $r) {
            $titulo = $r->summary ?? '';
            $chamada = $titulo;

            $dataInicialReal = preg_replace(['/\-[0-9]{2}\:[0-9]{2}$/', '/T/'], ['', ' '], $r->start->dateTime ?? $r->start->date);
            $dataFinal = preg_replace(['/\-[0-9]{2}\:[0-9]{2}$/', '/T/'], ['', ' '], $r->end->dateTime ?? $r->end->date);

            $dataInicial = $Data->valor($dataInicialReal)->formato('Y-m-d');
            $dataFinal = $Data->valor($dataFinal)->formato('Y-m-d');

            $semana = $Data->valor($dataInicial)->nomeSemana();
            $mes = $Data->valor($dataInicial)->nomeMes();
            $dia = $Data->valor($dataInicial)->formato('d');

            $hora = '';
            $horaInicial = '';
            if (object_key_exists('dateTime', $r->start)) {
                $horaInicial = $Data->valor($dataInicialReal)->formato('H:i');
                $chamada = $horaInicial . ' ' . $chamada;
                $hora = ' - ' . $horaInicial;
            }
            $horaFinal = '';
            if (object_key_exists('dateTime', $r->end)) {
                $horaFinal = $Data->valor(preg_replace(['/\-[0-9]{2}\:[0-9]{2}$/', '/T/'], ['', ' '], $r->end->dateTime))->formato('H:i');
                $hora .= ' até ' . $horaFinal;
            }

            $statusLista = [
                'accepted' => 'sim',
                'tentative' => 'talvez',
                'declined' => 'nao'
            ];
            $status = '';
            $convidado = [];
            foreach ($r->attendees ?? [] as $item) {
                $nome = '';
                if (object_key_exists('displayName', $item)) {
                    $nome = $item->displayName;
                } elseif (object_key_exists('email', $item)) {
                    $nome = $this->pegarNomePeloEmail($item->email);
                }
                $souEu = object_key_exists('self', $item) && $item->self == 1;
                $convidadoStatus = in_array($item->responseStatus, array_keys($statusLista)) ? $statusLista[$item->responseStatus] : '';
                $convidado[] = [
                    'eu' => $souEu,
                    'nome' => $nome,
                    'imagem' => $this->pegarImagemPeloEmail($item->email),
                    'email' => $item->email,
                    'status' => $convidadoStatus,
                ];
                if ($souEu) {
                    $status = !empty($convidadoStatus) ? $convidadoStatus : 'aguardando';
                }
            }

            $video = [
                'imagem' => '',
                'nome' => '',
                'link' => '',
            ];
            foreach ($r->conferenceData->entryPoints ?? [] as $item) {
                if ($item->entryPointType == 'video') {
                    $video = [
                        'imagem' => $r->conferenceData->conferenceSolution->iconUri,
                        'nome' => $r->conferenceData->conferenceSolution->name,
                        'link' => $item->uri,
                    ];
                    break;
                }
            }

            $arquivo = [];
            foreach ($r->attachments ?? [] as $item) {
                $arquivo[] = [
                    'link' => $item->fileUrl,
                    'imagem' => $item->iconLink,
                    'nome' => $item->title,
                ];
            }

            $dono = '';
            if (object_key_exists('displayName', $r->creator)) {
                $dono = $r->creator->displayName;
            } elseif (object_key_exists('email', $r->creator)) {
                $dono = $r->creator->email;
            }

            $retorno[] = [
                'id' => $this->setarId($r->id),
                'chamada' => $chamada,
                'titulo' => $titulo,
                'descricao' => str_replace(PHP_EOL, '<br>', strip_tags($r->description ?? '', '<br>')),
                'inicio' => $semana . ', ' . $dia . ' de ' . $mes . $hora,
                'data' => [
                    'inicial' => $dataInicial,
                    'final' => $dataFinal
                ],
                'hora' => [
                    'inicial' => $horaInicial,
                    'final' => $horaFinal
                ],
                'local' => $r->location ?? '',
                'dono' => [
                    'eu' => $r->creator->email == $this->meuEmail(),
                    'nome' => $dono,
                    'email' => $r->creator->email,
                    'imagem' => $this->pegarImagemPeloEmail($r->creator->email)
                ],
                'link' => $r->htmlLink,
                'convidado' => $convidado,
                'video' => $video,
                'arquivo' => $arquivo,
                'status' => $status
            ];
        }

        return $retorno;
    }
}
