<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Documentos extends Model
{
    public static function getAll()
    {
        return Storage::allDirectories('public/syllos');

    }
}
