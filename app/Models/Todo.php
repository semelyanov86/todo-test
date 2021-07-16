<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'content', 'due_date', 'is_done'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'due_date' => 'datetime',
        'is_done' => 'boolean',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
