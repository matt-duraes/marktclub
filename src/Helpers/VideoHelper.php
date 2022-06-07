<?php

namespace Helpers;

final class VideoHelper
{
    private $link;
    private $plataforma;
    private $id;

    public function link($link)
    {
        $this->link = $link;

        $link = mb_strtolower($this->link, 'UTF-8');

        if (strstr($link, 'youtube')) {
            $explode = explode('v=', $this->link);
            $explode = end($explode);
            $explode = explode('&', $explode);
            $explode = explode('/', $explode[0] ?? '');

            $this->id = $explode[0] ?? '';
            $this->plataforma = 'youtube';
        } elseif (strstr($link, 'youtu.be')) {
            $explode = explode('/', $this->link);
            $explode = end($explode);
            $explode = explode('&', $explode);

            $this->id = $explode[0] ?? '';
            $this->plataforma = 'youtube';
        } elseif (strstr($link, 'vimeo')) {
            $explode = explode('/', $this->link);
            $explode = end($explode);
            $explode = explode('#', $explode);
            $explode = explode('&', $explode[0] ?? '');

            $this->id = $explode[0] ?? '';
            $this->plataforma = 'vimeo';
        }

        return $this;
    }

    public function iframe()
    {
        if (empty($this->id) || empty($this->plataforma)) {
            return '';
        }

        if ($this->plataforma == 'youtube') {
            return '<iframe src="https://www.youtube.com/embed/' . $this->id . '?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';
        } elseif ($this->plataforma == 'vimeo') {
            return '<iframe src="https://player.vimeo.com/video/' . $this->id . '?title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }

        return '';
    }

    public function imagem()
    {
        if (empty($this->id) || empty($this->plataforma)):
            return '';
        endif;

        if ($this->plataforma == 'youtube') {
            return 'http://i1.ytimg.com/vi/' . $this->id . '/hqdefault.jpg';
        } elseif ($this->plataforma == 'vimeo') {
            $video = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $this->id . '.php'));
            if (is_array($video) && isset($video[0]['thumbnail_large'])) {
                return $video[0]['thumbnail_large'];
            }
        }

        return '';
    }
}
