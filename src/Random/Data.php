<?php

namespace Random;

trait Data
{
    public function hoje(): string
    {
        return date('Y-m-d');
    }

    public function agora(): string
    {
        return date('Y-m-d H:i:s');
    }

    public function dataPassada(): string
    {
        return rand(1940, date('Y') - 1) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(
            rand(1, 28),
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    public function dataFutura(): string
    {
        return rand(date('Y') + 1, 2080) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(
            rand(1, 28),
            2,
            '0',
            STR_PAD_LEFT
        );
    }
}
