<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id','category_id','title','description','status','due_date'];

    protected $casts = [
        'user_id'     => 'integer',
        'category_id' => 'integer',
        'due_date'    => 'date',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function category(){ return $this->belongsTo(Category::class); }
}
