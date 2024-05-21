<?php

use App\Classes\SolicitacaoSalavip\Helper;

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('SALVAR VOUCHER DA SALA VIP')
    ->descricao('Requisição para salvar um novo voucher para a sala vip.')
    ->status(201)
    ->scope('solicitacao_salavip:salvar')
    ->metodo('post')
    ->uri('/solicitacao-salavip')

    ->headerToken()

    ->criptografar(Helper::CRIPTOGRAFAR)

    ->body('nome', 'João Rodrigues', 'Nome e Sobre nome do usuário', 'string', obrigatorio: true)
    ->body('cpf', '01234567890', 'CPF do usuário contendo apenas numeros', 'int', 11, obrigatorio: true)
    ->body('email_pessoal', 'email@dominio.com.br', 'E-mail pessoal.', 'string', obrigatorio: true)

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request POST '{{LINK}}/solicitacao-salavip' \
--header 'Authorization: Bearer {{TOKEN}}' \
--form 'nome=\"2fEegzzPSzh9rVefNQXzjg3VGO8e9ZUsAgo+v9mElxXb4dEJ8KhjQoMJKfvksc5xi+ocKIJ1NfUy+o179==\"' \
--form 'email_pessoal=\"8q0VVFGBL61g9/UlS5cAhFp/NCkctV3+SWFVCW+jK7TfQBH5vsRl12lo+dZCK/R2GHqMhg==\"' \
--form 'cpf=\"R989iFupMyrKkjjbmslw61xdK3P55GbsoYwg4aCwSr+EfL8vyH3zoLRsPhjWGEYRCE3CbW/MrFoEWiO==\"'")
    ->preSucesso('{
    "status": "sucesso",
    "dado": {
        "id": "e8b93b1d-6422-469c-8dfb-fc00889d66da",
        "usuario": {
            "id": "e8b93b1d-6422-469c-8dfb-fc00889d66da",
            "nome": "Nome do usuário",
            "cpf": 01234567890
        },
        "parceiro": {
            "id": "e8b93b1d-6422-469c-8dfb-fc00889d66da",
            "titulo": "Nome do parceiro",
            "imagem_logo": "https://arquivo.youhuul.com/logo_salavip.png"
        },
        "construtor": {
            "id": "e8b93b1d-6422-469c-8dfb-fc00889d66da",
            "logo_principal": "https://arquivo.youhuul.com/logo_principal.png",
            "logo_marktclub": "https://arquivo.youhuul.com/logo_secundaria.png"
        },
        "codigo": "codigo",
        "data_criacao": "' . date('Y-m-d H:i:s') . '",
        "data_vencimento": "' . date('Y-m-d') . '",
        "data_validacao": "",
        "qr_code": "https://qrcode.youhuul.com/voucher/codigo",
        "texto_desconto": "Texto do desconto.",
        "texto_voucher": "Texto do voucher.",
        "texto_juridico": "Texto jurídico",
        "texto_validar": "Texto do validar",
        "status": "criado"
    }
}')
    ->preFalha('{
    "status": "erro",
    "erro": {
        "titulo": "Campo obrigatório!",
        "mensagem": "O campo nome é obrigatório."
    }
}');

echo $Doc;
