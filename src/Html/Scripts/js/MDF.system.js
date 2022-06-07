class MDF {

    static async decode(html) {

        return html
            .replace(/<h1 class="mkf_h1">/g, '#').replace(/<\/h1>/g, '')
            .replace(/<h2 class="mkf_h2">/g, '##').replace(/<\/h2>/g, '')
            .replace(/<p class="mkf_p">/g, '').replace(/<\/p>/g, '')
            .replace(/<ul class="mkf_ul">\s/g, '').replace(/<\/ul>\s/g, '')
            .replace(/<ol class="mkf_ol">\s/g, '').replace(/<\/ol>\s/g, '')
            .replace(/<li class="mkf_ul_li">/g, '- ').replace(/<\/li>/g, '')
            .replace(/<li class="mkf_ol_li" data-mkf-numero="([0-9]+)">/g, '$1. ').replace(/<\/li>/g, '')
            .replace(/<b class="mkf_b">/g, '**').replace(/<\/b>/g, '**')
            .replace(/<i class="mkf_i">/g, '--').replace(/<\/i>/g, '--')
            .replace(/<a class="mkf_a" href="([a-zA-Z0-9\-\_\.\:\/]+)" target="_blank">([a-zA-Z0-9à-úÀ-Ú _-]+)<\/a>/g, '[$1]($2)');

    }
    static async encode(text) {

        let list = text.split(/\n/);

        let textFinal = [];
        let ul = false;
        let ol = false;
        let olNumero;
        let conteudo = false;
        [].forEach.call(list, p => {


            if (!conteudo && p.trim() == '') {
                return;
            } else if (!conteudo && p != '') {
                conteudo = true;
            }

            if (!/^\-\ /.test(p) && ul) {
                textFinal.push('</ul>');
                ul = false;
            }
            if (!/^[0-9]+\.\ /.test(p) && ol) {
                textFinal.push('</ol>');
                ol = false;
            }

            if (/^\-\ /.test(p)) {
                if (!ul) {
                    ul = true;
                    textFinal.push('<ul class="mkf_ul">');
                }
                p = '<li class="mkf_ul_li">' + p.replace('- ', '') + '</li>';
            } else if (/^[0-9]+\.\ /.test(p)) {
                if (!ol) {
                    ol = true;
                    textFinal.push('<ol class="mkf_ol">');
                }
                olNumero = p.split('. ')[0];
                p = '<li class="mkf_ol_li" data-mkf-numero="' + olNumero + '">' + p.replace(/^[0-9]+\.\ /, '') + '</li>';
            } else if (/^##/.test(p)) {
                p = p.replace('##', '');
                p = '<h2 class="mkf_h2">' + p + '</h2>';
            } else if (/^#/.test(p)) {
                p = p.replace('#', '');
                p = '<h1 class="mkf_h1">' + p + '</h1>';
            } else {
                p = '<p class="mkf_p">' + p + '</p>';
            }

            p = p.replace(/\*\*([a-zA-Zà-úÀ-Ú0-9<>\`\~\^\:\;\"\'\ \\\\|\/\?\!\@\#\$\%\&\(\)\-\_\=\+\,\.\/]+)\*\*/g, '<b class="mkf_b">$1</b>');
            p = p.replace(/\-\-([a-zA-Zà-úÀ-Ú0-9<>\`\~\^\:\;\"\'\ \\\\|\/\?\!\@\#\$\%\&\(\)\_\*\=\+\,\.\/]+)\-\-/g, '<i class="mkf_i">$1</i>');

            if (/(\[)(http:\/\/|https:\/\/)([a-zA-Z0-9-_.]+)(\]\()([a-zA-Z0-9à-úÀ-Ú _-]+)(\))/.test(p)) {
                p = p.replace(/(\[)(http:\/\/|https:\/\/)([a-zA-Z0-9-_.]+)(\]\()([a-zA-Z0-9à-úÀ-Ú _-]+)(\))/g, '<a class="mkf_a" href="$2$3" target="_blank">$5</a>');
            }

            textFinal.push(p);
        });

        let totalLine = textFinal.length;
        for (totalLine; totalLine >= 0; --totalLine) {
            if (textFinal[totalLine - 1] == '<p class="mkf_p"></p>') {
                textFinal.splice(totalLine - 1, 1);
            } else {
                totalLine = 0;
            }
        }
        return textFinal.join('\r\n');

    }

}