<?php

namespace App\Models\Api\View\Html;

use ORM\Entity;
use App\Classes\Geral\Status;

final class HtmlEntity extends Entity
{
    public Status $status;
}
