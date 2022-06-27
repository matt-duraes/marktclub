// @template "painel"

// window.addEventListener('load', () => {
//     const LINK = document.getElementById('LINK').value;

//     const botaoSalvar = document.getElementById('botao_atualizar_dado');

//     const inputNome = document.getElementById('input_nome');
//     const inputData = document.getElementById('input_data');
//     const inputGenero = document.getElementById('input_genero');
//     const inputEmailPessoal = document.getElementById('input_email_pessoal');
//     const inputTelefoneTrabalho = document.getElementById('input_telefone_trabalho');
//     const inputTelefonePessoal = document.getElementById('input_telefone_pessoal');
//     const inputHash = document.querySelector('#bloco_app_add input[name=form_system_hash]');

//     const listaInput = document.querySelectorAll(`
//         #input_nome, #input_data, .input_select_texto, #input_email_pessoal,
//         #input_telefone_trabalho, #input_telefone_pessoal
//     `);
//     const blocoSelect = document.getElementById('bloco_fw_select');
//     listaInput.forEach(input => {
//         input.addEventListener('keydown', e => {
//             if (
//                 input.classList.contains('input_select_texto') &&
//                 window.getComputedStyle(blocoSelect).getPropertyValue('display') == 'block'
//             ) {
//                 return;
//             }
//             if (e.key == 'Enter') {
//                 e.preventDefault();
//                 acaoParaAtualizarDado();
//             }
//         });
//     });

//     botaoSalvar.addEventListener('click', () => {
//         acaoParaAtualizarDado();
//     });
//     const acaoParaAtualizarDado = async () => {
//         if (botaoSalvar.classList.contains('aguarde')) {
//             return;
//         }

//         botaoSalvar.classList.add('aguarde');

//         let body = new FormData();
//         body.append('nome', inputNome.value);
//         body.append('data_nascimento', inputData.value);
//         body.append('genero', inputGenero.value);
//         body.append('email_pessoal', inputEmailPessoal.value);
//         body.append('telefone_trabalho', inputTelefoneTrabalho.value);
//         body.append('telefone_pessoal', inputTelefonePessoal.value);
//         body.append('form_system_hash', inputHash.value);
//         body.append('form_system_validacao', '');

//         const response = await fetch(LINK + '/perfil/dado', {
//             method: 'POST',
//             body,
//         });

//         botaoSalvar.classList.remove('aguarde');
//         if (response.status == 204) {
//             Alerta.notificacao('Dados alterados com sucesso!', true);
//             return;
//         }

//         fetchNotificacaoErro(response, 'Erro ao atualizar dados, por favor, tente novamente.');
//     };
// });
