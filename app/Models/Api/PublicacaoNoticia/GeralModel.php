<?php

namespace App\Models\Api\PublicacaoNoticia;

use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
use Modules\Data;
use ORM\ORM;

abstract class GeralModel extends ORM
{
    public const CAMPO = [
        'uuid', 'titulo_grande', 'titulo_pequeno', 'texto_grande', 'texto_pequeno',
        'data_inicio', 'data_final', 'imagem_grande', 'imagem_pequena', 'url', 'status',
        'autor_noticia', 'fonte_noticia', 'fonte_link'
    ];

    protected string $ormTabela = TABELA_PUBLICACAO_NOTICIA;

    protected function montardado($lista)
    {
        if (!$lista) {
            return [];
        }

        $Status = new Status();
        $retorno = [];
        foreach ($lista as $r) {
            $titulo = $r->titulo_pequeno;
            if (empty($titulo)) {
                $titulo = strCortar($r->titulo_grande, 80);
            }

            $texto = $r->texto_pequeno;
            if (empty($texto)) {
                $texto = strCortar(strip_tags($r->texto_grande), 120);
            }
            $imagem = '';
            if (!empty($r->imagem_pequena)) {
                $imagem = $r->imagem_pequena;
            } elseif (!empty($r->imagem_grande)) {
                $imagem = $r->imagem_grande;
            }

            $statusIndice = $Status->indice($r->status);
            $publicado = new Publicado(
                new Data($r->data_inicio),
                new Data($r->data_final),
                $statusIndice == Status::ATIVO
            );

            $retorno[] = [
                'id'            => $r->uuid,
                'titulo'        => $titulo,
                'texto'         => $texto,
                'imagem'        => !empty($imagem) ? arquivoPrivado($imagem) : '',
                'data_inicio'   => $r->data_inicio,
                'url'           => $r->url,
                'autor_noticia' => $r->autor_noticia,
                'fonte_noticia' => $r->fonte_noticia,
                'fonte_link'    => $r->fonte_link,
                'publicado'     => $publicado->indice(),
                'status'        => $statusIndice
            ];
        }
        return $retorno;
    }
}
