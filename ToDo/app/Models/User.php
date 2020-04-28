<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function addTodo(Todo $todo)
    {
        return $this->todos()->save($todo);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteTodo($todoId)
    {
        $this->todos()->find($todoId)->delete();
        return ["message"=>"The todo has been deleted"];
    }

    /**
     * @param $todoName
     * @return int
     */
    public function hasDuplicateTodo($todoName)
    {
        return $this->todos()->where("name", $todoName)->count();
    }
}