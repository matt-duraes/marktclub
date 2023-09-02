<?php

namespace ApiModel\Endereco;

final class RaioModel
{
    public array $latitude = [];
    public array $longitude = [];
    private float $latitudeEsquerda;
    private float $latitudeDireita;
    private float $longitudeEsquerda;
    private float $longitudeDireita;

    public function __construct(
        float $latitude,
        float $longitude,
        int $raio = 5
    ) {
        $this->latitudeEsquerda = $this->novaLatitude($latitude, $raio * -1);
        $this->latitudeDireita = $this->novaLatitude($latitude, $raio);
        $this->longitudeEsquerda = $this->novaLongitude($latitude, $longitude, $raio * -1);
        $this->longitudeDireita = $this->novaLongitude($latitude, $longitude, $raio);
        $this->montarLatitude();
        $this->montarLongitude();
    }

    private function montarLatitude()
    {
        if ($this->latitudeEsquerda > $this->latitudeDireita) {
            $this->latitude = [$this->latitudeEsquerda, $this->latitudeDireita];
            return;
        }
        $this->latitude = [$this->latitudeDireita, $this->latitudeEsquerda];
    }

    private function montarLongitude()
    {
        if ($this->longitudeEsquerda > $this->longitudeDireita) {
            $this->longitude = [$this->longitudeEsquerda, $this->longitudeDireita];
            return;
        }
        $this->longitude = [$this->longitudeDireita, $this->longitudeEsquerda];
    }

    private function novaLatitude($latitude, $km)
    {
        $raioTerra = 6371;
        $raioLatitude = deg2rad($latitude);
        $novaLatitude = $raioLatitude + ($km / $raioTerra);
        return rad2deg($novaLatitude);
    }

    private function novaLongitude($latitude, $longitude, $km)
    {
        $raioTerra = 6371;
        $raioLatitude = deg2rad($latitude);
        $raioLongitude = deg2rad($longitude);
        $anguloDistancia = $km / $raioTerra;
        $novaLongitude = $raioLongitude + $anguloDistancia / cos($raioLatitude);
        return rad2deg($novaLongitude);
    }
}
