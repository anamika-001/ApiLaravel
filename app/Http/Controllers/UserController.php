<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    public function getusers()
    {
        $users = User::get();
        return UserResource::collection($users);
    }
}
