// @template "painel"
// @painel "app_geral_add"

   const inputPadrao = document.querySelector('#input_padrao');

   if (inputPadrao.value == 1) {
       const inputStatus = document.querySelector('#input_status_texto');
       inputStatus.parentNode.classList.add('display_none');

       const blocoCheckBox = document.querySelector('.bloco_checkbox_geral');
       blocoCheckBox.parentNode.classList.add('display_none');
   }
