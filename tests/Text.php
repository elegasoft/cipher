<?php

namespace Elegasoft\Cipher\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Text extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return new TextFactory();
    }
}
