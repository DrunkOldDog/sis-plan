<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * @param  \App\User  $user
     * @return void
     */
    //Patron Observer para notificar al administrador cada vez que hay un cambio de privilegios en los usuarios
    public function updated(User $user)
    {
        //
        /*$basic  = new \Nexmo\Client\Credentials\Basic('ce0f5f46', '1dUfDZATgBmP937C');
        $client = new \Nexmo\Client($basic);

        if($user->isAdmin == 0){
            $message = $client->message()->send([
                'to' => '+59175809992',
                'from' => 'Hotel Empresarial',
                'text' => 'Se informa que al/a usuario/a: ' . $user->name . ' ' . $user->last_name . ' del ci ' . $user->ci . ' se le quito el privilegio de encargad@'
            ]);
        }else{
            $message = $client->message()->send([
                'to' => '+59175809992',
                'from' => 'Hotel Empresarial',
                'text' => 'Se informa que al/a usuario/a: ' . $user->name . ' ' . $user->last_name . ' del ci ' . $user->ci . ' se le dio el privilegio de encargad@'
            ]);
        }*/
    }
}
