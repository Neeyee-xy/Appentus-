<?php

namespace App\Services;

use App\Models\User;

class UserService
{   
    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        $user?->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        return User::destroy($id);
    }
    //... add other necessary methods
}