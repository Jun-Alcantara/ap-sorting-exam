<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'rank', 'name'];

    public function Group()
    {
        return $this->belongsTo(\App\Models\Group::class);
    }
}
