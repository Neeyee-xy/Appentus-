<?php

namespace App\Enums;

Enum UserType: string implements Base
{
    case ADMIN = 'Admin';
    case USER = 'User';
  
  

    public function title(): string
    {
        return match($this){
            self::ADMIN => 'Admin',
            self::USER => 'User',
            
           
        };
    }

    public function icon(): string
    {
        return match($this){
            self::ADMIN => '',
            self::USER => '',
            
           
        };
    }

    public function description(): string
    {
        return match($this){
            self::ADMIN => '',
            self::USER => '',
            
     
        };
    }
}
