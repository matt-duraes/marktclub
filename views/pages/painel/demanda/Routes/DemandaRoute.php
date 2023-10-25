<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )
    ::nome('demanda')
    ::controller(Painel\Demanda\Controllers\DemandaController::class)
    ::grupo(function () {
        Route
            ::nome('tecnologia')
            ::view('/demanda/tecnologia');
        Route
            ::nome('criacao')
            ::view('/demanda/criacao');
        Route
            ::nome('convenio')
            ::view('/demanda/convenio');
        Route
            ::nome('listar')
            ::request(['area', 'status'])
            ::post('/demanda/listar');

        Route
            ::nome('demanda')
            ::view('/demanda/demanda/{id}');
        Route
            ::nome('demandaSalvar')
            ::view('/demanda/demanda-salvar/{area}');
        Route
            ::nome('tarefaListar')
            ::post('/demanda/tarefa-listar/{demanda}');
        Route
            ::nome('tarefaSalvar')
            ::view('/demanda/tarefa-salvar/{demanda}');
        Route
            ::nome('tarefaEditar')
            ::view('/demanda/tarefa-editar/{id}/{demanda}');

        Route
            ::nome('demandaSalvar')
            ::request([
                'tipo', '!titulo', 'empresa_nome', 'empresa', '!dominio_tipo', '!dominio_link', '!login_api',
                '!login_link', '!app', '!texto', '!cdn', '!local', '!critico', '!criacao_site',
                '!criacao_social', '!criacao_impresso', '!criacao_kit', '!criacao_video', '!criacao_outro',
                '!site_largura', '!site_altura', '!digital_stories', '!digital_feed', '!digital_banner',
                '!feed_whatsapp', '!feed_instagram', '!feed_facebook', '!feed_linkedin', '!feed_twitter',
                '!feed_youtube', '!feed_tiktop', '!impresso_voucher', '!impresso_folder', '!impresso_banner',
                '!impresso_revista', '!impresso_outro', '!impresso_outro_texto', '!kit_email',
                '!kit_stories', '!kit_video', '!kit_feed', '!kit_como_acessar', '!kit_baixar_app',
                '!kit_previa', '!video_formato', '!video_largura', '!video_altura', '!outro_texto', '!site_texto',
                '!digital_texto', '!impresso_texto', '!kit_texto', '!video_texto', '!sorteio_inicio',
                '!sorteio_final', '!sorteio_data', '!sorteio_como_participar', '!sorteio_motivacao',
                '!sorteio_motivacao_outro', '!sorteio_premio', '!sorteio_premio_compra', '!sorteio_premio_entrega',
                '!sorteio_premio_entrega_outro', '!sorteio_texto', '!data_retorno', '!data_inicio', '!data_fim', '!metragem',
                '!participantes_quantidade', '!publico_esperado', '!parceiros_quantidade', '!esperado_empresa', '!materiais',
                '!resposavel_nome', '!responsavel_email', '!resposavel_telefone', '!cobertura', '!wifi', '!energia',
                '!agua', '!alimentacao', '!mesaCadeira', '!outros', '!observacao', '!inicio_divulgacao', '!fim_divulgacao',
                '!tema', '!segmento', '!participantes', '!instagram', '!facebook', '!email', '!flyer', '!divulgacao', '!usuario_nome',
                '!usuario_email', '!usuario_telefone', '!empresa_email', '!empresa_telefone', '!empresa_cep',
                '!empresa_logradouro', '!empresa_numero', '!empresa_complemento', '!empresa_bairro', '!empresa_cidade', '!empresa_estado',
                '!empresa_loja_fisica', '!empresa_indicada_nome', '!nome', '!ramo', '!email', '!telefone', '!loja_fisica',
                '!cep', '!logradouro', '!numero', '!complemento', '!bairro', '!cidade', '!estado',
            ])
                ::post('/demanda/demanda-salvar');
        Route
            ::nome('demandaEditar')
            ::view('/demanda/demanda-editar/{id}');
        Route
            ::nome('demandaEditar')
            ::request(['titulo', 'dono', 'empresa', 'com_prazo', 'data_entrega'])
            ::post('/demanda/demanda-editar/{id}');
        Route
            ::nome('demandaCancelar')
            ::request(['motivo'])
            ::post('/demanda/demanda-cancelar/{id}');
        Route
            ::nome('demandaLiberar')
            ::post('/demanda/demanda-liberar/{id}');
        Route
            ::nome('demandaOrdenar')
            ::request(['id'])
            ::post('/demanda/demanda-ordenar');
        Route
            ::nome('demandaStatus')
            ::request(['id', 'status'])
            ::post('/demanda/demanda-status');

        Route
            ::nome('demandaSeguir')
            ::request(['id'])
            ::post('/demanda/demanda-seguir');
        Route
            ::nome('demandaSeguirParar')
            ::request(['id'])
            ::post('/demanda/demanda-seguir-parar');

        Route
            ::nome('tarefaEditar')
            ::request(['titulo', 'texto', 'tipo'])
            ::post('/demanda/tarefa-editar/{id}');
        Route
            ::nome('tarefaSalvar')
            ::request(['demanda', 'titulo', 'texto', 'tipo'])
            ::post('/demanda/tarefa-salvar');
        Route
            ::nome('tarefaArquivo')
            ::request(['arquivo'])
            ::post('/demanda/tarefa-arquivo/{id}');
        Route
            ::nome('tarefa')
            ::delete('/demanda/tarefa/{id}');
        Route
            ::nome('tarefaLike')
            ::post('/demanda/tarefa-like/{id}');
        Route
            ::nome('tarefaDeslike')
            ::request(['motivo'])
            ::post('/demanda/tarefa-deslike/{id}');

        Route
            ::nome('trabalhoComecar')
            ::get('/demanda/trabalho-comecar/{tarefa}/{demanda}/{area}');
        Route
            ::nome('trabalhoAtualizar')
            ::get('/demanda/trabalho-atualizar/{id}');
        Route
            ::nome('trabalhoParar')
            ::get('/demanda/trabalho-parar/{id}');
        Route
            ::nome('trabalhoConcluir')
            ::get('/demanda/trabalho-concluir/{id}');
        Route
            ::nome('trabalhoMinimizar')
            ::get('/demanda/trabalho-minimizar/{acao}');
    });
