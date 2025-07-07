<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Qualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
    ];
} 