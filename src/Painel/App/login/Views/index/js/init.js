const blocoRecaptcha = $('#RECAPTCHA');
const RECAPTCHA = blocoRecaptcha.value;
blocoRecaptcha.remove();
const blocoRecaptchaV2 = $('#RECAPTCHA_V2');
const RECAPTCHAV2 = blocoRecaptchaV2.value;
blocoRecaptchaV2.remove();

const inputLogin = document.querySelector('#input_cpf');
const inputSenha = document.querySelector('#input_passe');
const hash = document.querySelector('input[name=form_system_hash]').value;

const blocoLogin = document.getElementById('bloco_conteudo');
const botaoLogin = document.getElementById('botao_login');
