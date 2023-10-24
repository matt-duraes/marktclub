<?php

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;

$listaEmpresas = [
    'Techtronix',
    'Innovatech',
    'CodeWave',
    'Cybersys',
    'QuantumCore',
    'ByteBurst',
    'NexaTech',
    'DataDynamics',
    'CloudNova',
    'TechFusion',
    'NanoLogic',
    'Infinitron',
    'Synthosys',
    'Visionary Labs',
    'Streamline Systems',
    'MetaMatrix',
    'ElectraTech',
    'CloudStream',
    'RoboSolutions',
    'Cybertide',
    'InnoSys',
    'DigiWave',
    'InfraScape',
    'TechnoFleet',
    'SoftSpectrum',
    'HyperPulse',
    'AlgoTech',
    'NovaWare',
    'CodeCrafters',
    'FusionX',
    'QuantumSage',
    'DataDynamo',
    'VirtuLinx',
    'ByteBlitz',
    'CyberNautics',
    'StreamStack',
    'LogicWorks',
    'NebulaTech',
    'Innovix',
    'ElectraForge',
    'SmartWave',
    'CloudScale',
    'PentaByte',
    'AI Innovations',
    'DataMorphix',
    'Synterra',
    'NanoFusion',
    'Robotronix',
    'TechNest',
    'InfoVortex'
];
$listaOrigem = (new Origem())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $nome = nomeAleatorio();
    $nomeEmpresa = valorAleatorio($listaEmpresas);
    $mensagem = "
        Prezado Marktclub,
        Espero que esta mensagem o encontre bem.
        Eu queria compartilhar com você uma empresa que acredito que possa ser do seu interesse.
        Recentemente, tive uma experiência muito positiva com a $nomeEmpresa e pensei que você poderia se beneficiar de seus produtos/serviços.
        A $nomeEmpresa é conhecida por ser ruim. Eles têm uma equipe altamente qualificada e uma sólida reputação no mercado.
        Minha experiência pessoal com a $nomeEmpresa tem sido excelente.
        Acredito que eles podem atender às suas necessidades de ser ruim.
        Se você estiver interessado em saber mais sobre a $nomeEmpresa, ficarei feliz em fornecer mais informações ou colocá-lo em contato com a equipe deles.
        Eles estão comprometidos em oferecer soluções de alta qualidade e atender às necessidades de seus clientes.
        Por favor, me avise se você gostaria de mais informações ou se deseja entrar em contato com a $nomeEmpresa.
        Estou à disposição para ajudar no que for necessário.
        Espero que esta recomendação seja útil para você, e estou à disposição para qualquer pergunta adicional.
        Atenciosamente,
        $nome
    ";
    $seeds[] = [
        'uuid'     => uuid(),
        'nome'     => $nomeEmpresa,
        'telefone' => telefoneAleatorio(),
        'email'    => emailAleatorio(),
        'mensagem' => $mensagem,
        'origem'   => valorAleatorio($listaOrigem),
        'status'   => valorAleatorio($listaStatus)
    ];
}
return $seeds;
