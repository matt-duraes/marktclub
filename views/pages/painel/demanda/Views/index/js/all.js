// @template "painel"
// @system "Popup"
// @system "Esqueleto"
// @import "init"
// @import "index"
// @import "demanda_detalhe"
// @import "demanda_salvar"
// @import "tarefa_lista"
// @import "tarefa_salvar"

// const blocoListaSessao = document.querySelectorAll('.bloco_kambam_index .conteudo_geral .bloco_coluna');

// removerItemDemanda = item => {
//     if (!item) {
//         return;
//     }
//     const bloco = item.closest('.bloco_coluna');
//     item.parentNode.removeChild(item);
//     removeuItemDemanda(bloco);
// };
// removeuItemDemanda = bloco => {
//     if (!bloco) {
//         return;
//     }
//     const blocoConteudo = bloco.querySelector('.conteudo');
//     const blocoQuantidade = bloco.querySelector('header h1 span');
//     const quantidade = blocoConteudo.querySelectorAll('article').length;
//     if (quantidade == 0) {
//         blocoConteudo.innerHTML = '<div class="tarefa_zero">Sem itens<br> no momento</div>';
//     }
//     blocoQuantidade.innerHTML = `(${quantidade})`;
// };
// moverItemDemanda = (destino, item) => {
//     if (!destino || !item) {
//         return;
//     }
//     const blocoAtual = item.closest('.bloco_coluna');
//     const blocoConteudo = destino.querySelector('.conteudo');
//     const blocoQuantidade = destino.querySelector('header h1 span');
//     const blocoZero = blocoConteudo.querySelector('.tarefa_zero');
//     if (blocoZero) {
//         blocoZero.parentNode.removeChild(blocoZero);
//     }
//     blocoConteudo.appendChild(item);
//     const quantidade = blocoConteudo.querySelectorAll('article').length;
//     blocoQuantidade.innerHTML = `(${quantidade})`;
//     removeuItemDemanda(blocoAtual);
// };

// window.addEventListener('load', () => {
//     const area = document.querySelector('#input_area').value || '';
//     /*
//     |--------------------------------------------------------------------------
//     | ABRIR ADD NOVO
//     |--------------------------------------------------------------------------
//     */
//     const botaoAdd = document.getElementById('botao_add_tarefa');
//     const PaginaAddTarefa = new Popup('demanda-salvar', $('#bloco_demanda_nova'), true, true, demandaSalvar);

//     botaoAdd.addEventListener('click', () => {
//         PaginaAddTarefa.abrir();
//     });

//     /*
//     |--------------------------------------------------------------------------
//     | ABRIR DETALHE DA DEMANDA
//     |--------------------------------------------------------------------------
//     */
//     const tarefaLista = document.querySelectorAll('#bloco_demanda_index article');
//     tarefaLista.forEach(tarefa => {
//         const id = tarefa.getAttribute('data-id');
//         const PaginaDetalhe = new Pagina(
//             'demanda-' + id,
//             LINK + '/demanda/demanda/' + id,
//             {},
//             true,
//             true,
//             demandaDetalhe
//         );
//         tarefa.addEventListener('click', e => {
//             if (e.target.classList.contains('botao_mover') || e.target.closest('.botao_mover')) {
//                 return;
//             }
//             PaginaDetalhe.abrir();
//         });
//     });

//     const blocoTarefaLiberada = document.getElementById('bloco_coluna_liberado');
//     reordenarTarefaLiberada = async () => {
//         const body = new FormData();
//         blocoTarefaLiberada.querySelectorAll('.bloco_tarefa_item').forEach(tarefa => {
//             body.append('id[]', tarefa.getAttribute('data-id'));
//         });
//         const resposta = await fetch(LINK + '/demanda/demanda-ordenar', {
//             method: 'POST',
//             body,
//         });
//         if (resposta.status == 204) {
//             return;
//         }
//         Alerta.notificacao('Erro ao ordenar tarefas, por favor, tente novamente.', false);
//     };
// });
