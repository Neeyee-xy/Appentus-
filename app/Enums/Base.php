<?php

namespace App\Enums;

interface Base
{
    public function title(): string;

    public function icon(): string;

    public function description(): string;
}
