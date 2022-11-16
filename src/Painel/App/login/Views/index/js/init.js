const blocoRecaptcha = document.getElementById('RECAPTCHA');
const RECAPTCHA = blocoRecaptcha.value;
blocoRecaptcha.parentNode.removeChild(blocoRecaptcha);

const blocoLink = document.getElementById('LINK');
const LINK = blocoLink.value;
blocoLink.parentNode.removeChild(blocoLink);

const inputLogin = document.querySelector('.input_input input[name=login]');
const inputSenha = document.querySelector('.input_input input[name=senha]');
const inputLogado = document.querySelector('.input_checkbox input[name=logado]');
const hash = document.querySelector('input[name=form_system_hash]').value;

const blocoLogin = document.getElementById('bloco_conteudo');
const botaoLogin = document.getElementById('botao_login');
