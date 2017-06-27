<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];

    //指定Status 对应 一个User 的关系
    public function user(){
        return $this->belongsTo(User::class);
    }
}
