<?php

namespace App\Classes\ParceiroLoja;

final class Helper
{
    public const CRIPTOGRAFAR = [];
    public const PERMISSAO_EMPRESA = 'parceiro_loja_empresa';
    public const PARAMETROS_LISTAR = [
        'pagina', '!quantidade', '!categoria', '!subcategoria', '!tipo_estabelecimento',
        '!pesquisa', '!titulo', '!tipo_loja', '!status', '!ordem', '!favorito', '!mais_acessado',
        '!latitude', '!longitude', '!endereco_estado', '!empresa', '!equipe', '!convenio_direto',
        '!convenio', '!data_criacao_de', '!data_criacao_ate', '!data_publicacao_de',
        '!data_publicacao_ate', '!data_prospeccao_de', '!data_prospeccao_ate', '!data_problema_de',
        '!data_problema_ate', '!data_cancelado_de', '!data_cancelado_ate', '!data_auditoria_de',
        '!data_auditoria_ate'
    ];
}
