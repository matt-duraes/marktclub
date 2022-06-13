const { src } = require('gulp');
const prop = require('yargs').argv;
const prompt = require('gulp-prompt');
const { fsCriarDiretorio } = require('./arquivo.js');
const fs = require('fs');

exports.configVerificar = async function () {
    await new Promise(resolve => {
        src('./')
            .pipe(
                prompt.prompt(
                    [
                        {
                            type: 'input',
                            name: 'titulo',
                            message: 'Digite um título para o sistema:',
                            validate: titulo => {
                                return titulo != '';
                            },
                        },
                        {
                            type: 'input',
                            name: 'public',
                            default: 'public',
                            message: 'Digite o diretório público sem iniciar com "/" (padrão: public):',
                            validate: public => {
                                return public != '' && !/^\//.test(public);
                            },
                        },
                        {
                            type: 'input',
                            name: 'url',
                            default: 'localhost',
                            message: 'Digite a URL local sem o protocolo (padrao: localhost):',
                            validate: url => {
                                return !/^http(s)?\:\/\//.test(url);
                            },
                        },
                        {
                            type: 'input',
                            name: 'dockerHttp',
                            default: 80,
                            message: 'Digite uma porta para o HTTP (padrao: 80):',
                            validate: porta => {
                                return /^[1-9]{1}[0-9]{0,}$/.test(porta);
                            },
                        },
                        {
                            type: 'input',
                            name: 'dockerHttps',
                            default: 443,
                            message: 'Digite uma porta para o HTTPS (padrao: 443):',
                            validate: porta => {
                                return /^[1-9]{1}[0-9]{0,}$/.test(porta);
                            },
                        },
                        {
                            type: 'input',
                            name: 'dockerDb',
                            default: 3306,
                            message: 'Digite uma porta para o Bando de Dados (padrao: 3306):',
                            validate: porta => {
                                return /^[1-9]{1}[0-9]{0,}$/.test(porta);
                            },
                        },
                        {
                            type: 'input',
                            name: 'dockerPma',
                            default: 8080,
                            message: 'Digite uma porta para o PhpMyAdmin (padrao: 8080):',
                            validate: porta => {
                                return /^[1-9]{1}[0-9]{0,}$/.test(porta);
                            },
                        },
                        {
                            type: 'input',
                            name: 'browserProxy',
                            default: 3000,
                            message: 'Digite uma porta para o LiveServer (padrao: 3000):',
                            validate: porta => {
                                return /^[1-9]{1}[0-9]{0,}$/.test(porta);
                            },
                        },
                        {
                            type: 'input',
                            name: 'dbNome',
                            message: 'Digite um nome para o banco de dados:',
                            validate: nome => {
                                return nome != '';
                            },
                        },
                        {
                            type: 'password',
                            name: 'dbSenha',
                            message: 'Digite uma senha para o banco de dados:',
                            validate: senha => {
                                return senha != '';
                            },
                        },
                        {
                            type: 'input',
                            name: 'virus',
                            message: 'Digite uma key do virustotal.com (opcional):',
                        },
                        {
                            type: 'input',
                            name: 'google',
                            message: 'Digite uma key do Google Safe Browsing (opcional):',
                        },
                        {
                            type: 'input',
                            name: 'gitOrigin',
                            message: 'Digite a url do Origin do GIT:',
                            validate: gitOrigin => {
                                return gitOrigin != '';
                            },
                        },
                        {
                            type: 'input',
                            name: 'gitUpstream',
                            message: 'Digite a url do Upstream do GIT:',
                            validate: gitUpstream => {
                                return gitUpstream != '';
                            },
                        },
                    ],
                    response => {
                        respostas = response;
                        console.log('');
                        console.log('\x1b[1mGostaria de confirmar as respostas?\033[0m');
                        console.log('Título: \x1b[1m' + response.titulo + '\033[0m');
                        console.log('Diretório: \x1b[1m' + response.public + '\033[0m');
                        console.log('Url: \x1b[1m' + response.url + '\033[0m');
                        console.log('Porta HTTP: \x1b[1m' + response.dockerHttp + '\033[0m');
                        console.log('Porta HTTPS: \x1b[1m' + response.dockerHttps + '\033[0m');
                        console.log('Porta Banco: \x1b[1m' + response.dockerDb + '\033[0m');
                        console.log('Porta PhpMyAdmin: \x1b[1m' + response.dockerPma + '\033[0m');
                        console.log('Porta LiveServer: \x1b[1m' + response.browserProxy + '\033[0m');
                        console.log('Nome do Banco: \x1b[1m' + response.dbNome + '\033[0m');
                        console.log('Senha do Banco: \x1b[1m******\033[0m');
                        console.log('virustotal.com: \x1b[1m' + response.virus + '\033[0m');
                        console.log('Google: \x1b[1m' + response.google + '\033[0m');
                        console.log('Git Origin: \x1b[1m' + response.gitOrigin + '\033[0m');
                        console.log('Git Upstream: \x1b[1m' + response.gitUpstream + '\033[0m');
                    }
                )
            )
            .pipe(prompt.confirm('Digite "y" para sim ou "N" para não:'))
            .on('end', resolve);
    });

    const titulo = respostas.titulo;
    const nome = titulo.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
    const public = respostas.public;
    const url = respostas.url;
    const dockerHttp = respostas.dockerHttp;
    const dockerHttps = respostas.dockerHttps;
    const dockerDb = respostas.dockerDb;
    const dockerPma = respostas.dockerPma;
    const browserProxy = respostas.browserProxy;
    const dbNome = respostas.dbNome;
    const dbSenha = respostas.dbSenha;
    const virus = respostas.virus;
    const google = respostas.google;
    const gitOrigin = respostas.gitOrigin;
    const gitUpstream = respostas.gitUpstream;

    let configJson = fs
        .readFileSync('./src/Files/gulp.json', 'utf-8')
        .replace(/\{\{titulo\}\}/g, titulo)
        .replace(/\{\{nome\}\}/g, nome)
        .replace(/\{\{public\}\}/g, public)
        .replace(/\{\{url\}\}/g, url)
        .replace(/\{\{dockerHttps\}\}/g, dockerHttps)
        .replace(/\{\{dockerHttp\}\}/g, dockerHttp)
        .replace(/\{\{dockerDb\}\}/g, dockerDb)
        .replace(/\{\{dockerPma\}\}/g, dockerPma)
        .replace(/\{\{browserProxy\}\}/g, browserProxy)
        .replace(/\{\{dbNome\}\}/g, dbNome)
        .replace(/\{\{dbSenha\}\}/g, dbSenha)
        .replace(/\{\{virus\}\}/g, virus)
        .replace(/\{\{google\}\}/g, google)
        .replace(/\{\{gitOrigin\}\}/g, gitOrigin)
        .replace(/\{\{gitUpstream\}\}/g, gitUpstream);

    await fsCriarDiretorio('./files');
    await fsCriarDiretorio('./files/config');

    const pathDest = './files/config/gulp.json';
    if (await fs.existsSync(pathDest)) {
        await fs.unlink(pathDest, function (err) {});
    }
    await new Promise(resolve => setTimeout(resolve, 500));
    await fs.appendFile(pathDest, configJson, function (err) {});
    await new Promise(resolve => setTimeout(resolve, 2000));

    fs.writeFileSync('./files/config/.config', '1');

    return Promise.resolve();
};
