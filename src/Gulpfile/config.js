const { src } = require('gulp');
const prompt = require('gulp-prompt');
const { fsCriarDiretorio, fsRemoverArquivoSeExistir, fsCriarArquivo } = require('./arquivo.js');
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
                            message: 'Digite o link ssh do Origin do projeto:',
                            validate: gitOrigin => {
                                return gitOrigin != '';
                            },
                        },
                        {
                            type: 'input',
                            name: 'gitUpstream',
                            message: 'Digite o link ssh do Upstream do projeto:',
                            validate: gitUpstream => {
                                return gitUpstream != '';
                            },
                        },
                        {
                            type: 'input',
                            name: 'gitEnv',
                            message: 'Digite o link ssh do repositório ENV: (opcional):',
                        },
                        {
                            type: 'input',
                            name: 'gitFw',
                            message: 'Digite o link ssh do repositório do Framework: (opcional):',
                        },
                        {
                            type: 'input',
                            name: 'gitArquivo',
                            message: 'Digite o link ssh do repositório de arquivo: (opcional):',
                        },
                    ],
                    response => {
                        respostas = response;
                        console.log('');
                        console.log('\x1b[1mGostaria de confirmar as respostas?\x1b[0m');
                        console.log('Título: \x1b[1m' + response.titulo + '\x1b[0m');
                        console.log('Diretório: \x1b[1m' + response.public + '\x1b[0m');
                        console.log('Porta HTTP: \x1b[1m' + response.dockerHttp + '\x1b[0m');
                        console.log('Porta HTTPS: \x1b[1m' + response.dockerHttps + '\x1b[0m');
                        console.log('Porta Banco: \x1b[1m' + response.dockerDb + '\x1b[0m');
                        console.log('Porta PhpMyAdmin: \x1b[1m' + response.dockerPma + '\x1b[0m');
                        console.log('Porta LiveServer: \x1b[1m' + response.browserProxy + '\x1b[0m');
                        console.log('Nome do Banco: \x1b[1m' + response.dbNome + '\x1b[0m');
                        console.log('Senha do Banco: \x1b[1m******\x1b[0m');
                        console.log('virustotal.com: \x1b[1m' + response.virus + '\x1b[0m');
                        console.log('Google: \x1b[1m' + response.google + '\x1b[0m');
                        console.log('Git Origin: \x1b[1m' + response.gitOrigin + '\x1b[0m');
                        console.log('Git Upstream: \x1b[1m' + response.gitUpstream + '\x1b[0m');
                        console.log('Git Env: \x1b[1m' + response.gitEnv + '\x1b[0m');
                        console.log('Git FrameWork: \x1b[1m' + response.gitFw + '\x1b[0m');
                        console.log('Git Arquivo: \x1b[1m' + response.gitArquivo + '\x1b[0m');
                    }
                )
            )
            .pipe(prompt.confirm('Digite "y" para sim ou "N" para não:'))
            .on('end', resolve);
    });

    const titulo = respostas.titulo;
    const nome = titulo.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
    const public = respostas.public;
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
    const gitEnv = respostas.gitEnv;
    const gitFw = respostas.gitFw;
    const gitArquivo = respostas.gitArquivo;

    let configJson = fs
        .readFileSync('./src/Files/gulp.json', 'utf-8')
        .replace(/\{\{titulo\}\}/g, titulo)
        .replace(/\{\{nome\}\}/g, nome)
        .replace(/\{\{public\}\}/g, public)
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
        .replace(/\{\{gitUpstream\}\}/g, gitUpstream)
        .replace(/\{\{gitEnv\}\}/g, gitEnv)
        .replace(/\{\{gitFw\}\}/g, gitFw)
        .replace(/\{\{gitArquivo\}\}/g, gitArquivo);

    await fsCriarDiretorio('./files');
    await fsCriarDiretorio('./files/config');

    const pathDest = './files/config/gulp.json';
    await fsRemoverArquivoSeExistir(pathDest);
    await fsCriarArquivo(pathDest, configJson);
    // await new Promise(resolve => setTimeout(resolve, 500));
    // await new Promise(resolve => setTimeout(resolve, 2000));

    fs.writeFileSync('./files/config/.config', '1');

    return Promise.resolve();
};
