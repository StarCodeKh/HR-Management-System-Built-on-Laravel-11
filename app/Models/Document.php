<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'document_type',
        'file_name',
        'file_path',
        'file_size',
        'file_extension',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}