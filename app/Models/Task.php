<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'author_id',
        'created_by',
        'deleted_by',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function taskHistories()
    {
        return $this->hasMany(TaskHistory::class);
    }
}