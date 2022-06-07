$(function () {
    $('input[name=marcar]').change(function () {
        if ($(this).is(':checked')) {
            $('.conteudo input').prop('checked', true);
        } else {
            $('.conteudo input').prop('checked', false);
        }
    });

    $('.conteudo input').change(function () {
        if ($('.conteudo input:checked').length == $('.conteudo input').length) {
            $('input[name=marcar]').prop('checked', true);
        } else {
            $('input[name=marcar]').prop('checked', false);
        }
    });

    $('button').click(function () {
        if (!$('input[name=aceito]').is(':checked')) {
            alert('Marque a caixa de alerta com os termos para continuar.');
            return false;
        }

        let tabela = [];
        $('.conteudo input:checked').each(function () {
            tabela.push($(this).val());
        });

        if (tabela == '') {
            alert('Marque pelo menos uma tabela para continuar.');
            return false;
        }
    });
});
