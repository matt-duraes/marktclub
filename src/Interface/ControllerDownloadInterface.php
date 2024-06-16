<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerDownloadInterface
{
    public function postDownload(Request $request): Response;
}
