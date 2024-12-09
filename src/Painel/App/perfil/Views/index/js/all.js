// @template "painel"

window.addEventListener('load', () => {
    const menuConfig = $('#botao_menu_config');
    const perfilImagemPrincipal = $('#perfil_imagem_principal');
    const perfilImagemMenuConfig = $('#menu_config_imagem_perfil');
    /*
    |--------------------------------------------------------------------------
    | MUDAR FOTO
    |--------------------------------------------------------------------------
    */
    const botaoAbrirMudarImagem = document.getElementById('botao_abrir_mudar_imagem');
    const blocoMudarImagem = document.getElementById('bloco_perfil_imagem');

    const abrirBlocoMudarPerfil = () => {
        blocoMudarImagem.classList.add('display_flex');
        setTimeout(() => {
            blocoMudarImagem.classList.add('abrir');
        }, 20);
    };

    if (location.hash && location.hash === '#mudar-imagem') {
        history.replaceState({}, '', location.href.replace('#mudar-imagem', ''));
        abrirBlocoMudarPerfil();
    }

    botaoAbrirMudarImagem.addEventListener('click', () => {
        abrirBlocoMudarPerfil();
    });
    const fecharPopupMudarImagem = () => {
        blocoMudarImagem.classList.remove('abrir');
        setTimeout(() => {
            blocoMudarImagem.classList.remove('display_flex');
        }, 300);
    };

    const botaoPerfilImagemFecharDesktop = document.getElementById('botao_perfil_imagem_fechar_desktop');
    const botaoPerfilImagemFecharMobile = document.getElementById('botao_perfil_imagem_fechar_mobile');
    botaoPerfilImagemFecharDesktop.addEventListener('click', () => {
        fecharPopupMudarImagem();
    });
    botaoPerfilImagemFecharMobile.addEventListener('click', () => {
        fecharPopupMudarImagem();
    });
    blocoMudarImagem.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_perfil_imagem') {
            fecharPopupMudarImagem();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | ARQUIVO
    |--------------------------------------------------------------------------
    */
    const botaoArquivoUpload = document.querySelector('#botao_upload_imagem_arquivo');
    const blocoArquivoPerfil = document.querySelector('#bloco_upload_imagem_perfil');
    botaoArquivoUpload.addEventListener('dragover', e => {
        e.preventDefault();
    });
    blocoMudarImagem.addEventListener('dragover', e => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        blocoArquivoPerfil.classList.add('drag');
    });
    blocoMudarImagem.addEventListener('dragleave', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
    });
    blocoMudarImagem.addEventListener('drop', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
        return;
    });

    const hash = document.querySelector('#bloco_perfil_imagem input[name=form_system_hash]').value;
    botaoArquivoUpload.addEventListener('drop', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
        if (e.dataTransfer.files.length == 0) {
            return;
        }

        fazerUploadDoArquivo(e.dataTransfer.files[0]);
    });
    botaoArquivoUpload.addEventListener('change', () => {
        const arquivo = botaoArquivoUpload.files[0];
        if (!arquivo) {
            return;
        }
        botaoArquivoUpload.value = '';
        fazerUploadDoArquivo(arquivo);
    });

    const fazerUploadDoArquivo = async arquivo => {
        Loading.show();

        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/perfil/imagem', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (response.status == 201) {
            fecharPopupMudarImagem();
            setarNovaImagem(json.dado.imagem);
            return;
        }

        Alerta.notificacao(json.erro.mensagem, false);
    };

    const setarNovaImagem = imagem => {
        if (menuConfig) {
            menuConfig.style.backgroundImage = 'url(' + imagem + ')';
        }
        if (perfilImagemPrincipal) {
            perfilImagemPrincipal.style.backgroundImage = 'url(' + imagem + ')';
        }
        if (perfilImagemMenuConfig) {
            perfilImagemMenuConfig.style.backgroundImage = 'url(' + imagem + ')';
        }
    };
});
