<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public static function boot(){
        parent::boot();

        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    //指定一个用户对应多条微博
    public function statuses(){
        return $this->hasMany(Status::class);
    }

    /*
     * 当前用户发布过的所有微博从数据库中取出
     */
    public function feed(){

        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
        return Status::whereIn('user_id',$user_ids)->with('user')
            ->orderBy('created_at','desc');

        //return $this->statuses()->orderBy();
    }

    /*
     * 获取粉丝关系列表
     */
    public function followers(){
        //return $this->belongsToMany(User::class,'followers', 'user_id', 'follower_id');
          return $this->belongsToMany(User::Class,'followers', 'user_id', 'follower_id');
    }

    /*
     * 获取用户关注人列表
     */
    public function followings(){
        //return $this->belongsToMany(User::class,'followers', 'follower_id', 'user_id');
          return $this->belongsToMany(User::Class,'followers', 'follower_id', 'user_id');
    }

    /*
     * 关注方法
     */
    public function follow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    /**
     * 取消关注
     * @param $user_ids
     */
    public function unfollow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 判断是否关注$user_id用户
     * @param string $user_id
     * @return mixed
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }

}
