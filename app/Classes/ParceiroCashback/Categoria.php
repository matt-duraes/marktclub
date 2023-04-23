<?php

namespace App\Classes\ParceiroCashback;

use Status\Status;

final class Categoria extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                'carros-e-motos' => 'Carros e Motos',
                'servicos-de-internet' => 'Serviços de Internet',
                'escolas-e-escritorios' => 'Escolas e escritórios',
                'familia-esportes-e-jogos' => 'Família, Esportes e Jogos',
                'computadores-e-games' => 'Computadores e Games',
                'noticias-e-informacoes' => 'Notícias e Informações',
                'casa-e-jardinagem' => 'Casa e Jardinagem',
                'arte-e-cultura' => 'Arte e Cultura',
                'passagens-aereas-e-pacotes-de-viagem' => 'Passagens aéreas e pacotes de viagem',
                'cidades-paises-e-regioes' => 'Cidades, Países e Regiões',
                'filmes-livros-e-musica' => 'Filmes, Livros e Música',
                'seguros-e-creditos' => 'Seguros e Créditos',
                'saude-e-cuidados' => 'Sáude e Cuidados',
                'comidas-e-bebidas' => 'Comidas e Bebidas',
                'presentes-e-flores' => 'Presentes e Flores ',
                'roupas-e-acessorios' => 'Roupas e Acessórios',
                'compras' => 'Compras',
                'cameras-e-filmadoras' => 'Câmeras e Filmadoras',
                'pagina-pessoal' => 'Página Pessoal',
                'tv-video-som-e-imagem' => 'Tv, Video, Som e Imagem',
                'ingressos' => 'Ingressos',
                'produto-para-casa' => 'Produto para casa ',
                'celulares-telefones-e-fax' => 'Celulares, Telefones e Fax',
                'outros' => 'Outros',
            ]
        );
    }
}
