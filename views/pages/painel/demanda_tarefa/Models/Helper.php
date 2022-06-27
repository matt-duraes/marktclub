<?php

namespace Painel\Demanda\Models;

final class Helper
{
    /*
    |--------------------------------------------------------------------------
    | TIPO
    |--------------------------------------------------------------------------
    */
    public function pegarNomeTipo(int $tipo): string
    {
        $lista = [
            1 => 'bug'
        ];
        return $lista[$tipo] ?? '';
    }

    public function pegarIconeTipo(int $tipo): string
    {
        $lista = [
            1 => iconeBug()
        ];
        return $lista[$tipo] ?? '';
    }
    public function pegarCorTipo(int $tipo): string
    {
        $lista = [
            1 => 'vermelho'
        ];
        return $lista[$tipo] ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | PRIORIDADE
    |--------------------------------------------------------------------------
    */
    public function pegarNomePrioridade(int $prioridade): string
    {
        $lista = [
            1 => 'Baixa',
            2 => 'Média',
            3 => 'Alta'
        ];
        return $lista[$prioridade] ?? '';
    }

    public function pegarIconePrioridade(int $prioridade): string
    {
        $lista = [
            1 => '<svg height="12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 36 40" style="enable-background:new 0 0 36 40;" xml:space="preserve"><path d="M1,23.8L15.6,39c1.3,1.4,3.5,1.4,4.9,0L35,23.8c1.3-1.4,1.3-3.7,0-5.1c-1.3-1.4-3.5-1.4-4.9,0l-8.5,8.8V3.7 C21.6,1.7,20,0,18,0s-3.6,1.7-3.6,3.7v24l-8.6-8.9c-1.3-1.4-3.5-1.4-4.9,0C-0.3,20.2-0.3,22.4,1,23.8z"/></svg>',
            2 => '<svg height="3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 8" style="enable-background:new 0 0 30 8;" xml:space="preserve"><path d="M25.9,0H19h-8H4.1C1.9,0,0,1.8,0,4s1.9,4,4.1,4H11h8h6.9C28.1,8,30,6.2,30,4S28.1,0,25.9,0z"/></svg>',
            3 => '<svg height="12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 36 40" style="enable-background:new 0 0 36 40;" xml:space="preserve"><path d="M35,16.2L20.4,1c-1.3-1.4-3.5-1.4-4.9,0L1,16.2c-1.3,1.4-1.3,3.7,0,5.1c1.3,1.4,3.5,1.4,4.9,0l8.5-8.8v23.9 c0,2,1.6,3.7,3.6,3.7s3.6-1.7,3.6-3.7v-24l8.6,8.9c1.3,1.4,3.5,1.4,4.9,0C36.3,19.8,36.3,17.6,35,16.2z"/></svg>'
        ];
        return $lista[$prioridade] ?? '';
    }

    public function pegarCorPrioridade(int $prioridade): string
    {
        $lista = [
            1 => 'verde',
            2 => 'azul',
            3 => 'vermelho'
        ];
        return $lista[$prioridade] ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */
    public function pegarNomeStatus(int $status)
    {
        $lista = [
            1 => 'To Do',
            2 => 'Liberado',
            3 => 'Em progresso',
            4 => 'Review',
            5 => 'Teste',
            6 => 'Deploy'
        ];
        return $lista[$status] ?? '';
    }
}
