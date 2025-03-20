<?php

namespace App\Listeners;

use App\Models\User;
use Dcblogdev\MsGraph\Facades\MsGraph;
use Dcblogdev\MsGraph\Models\MsGraphToken;
use Illuminate\Support\Facades\Auth;

class NewMicrosoft365SignInListener
{
    public function handle($event)
    {
        // azure ad data
        $name = $event->token['info']['displayName'];
        $email = $event->token['info']['mail'];
        $mobile = $event->token['info']['mobilePhone'];
        $title = $event->token['info']['jobTitle'];
        $location = $event->token['info']['officeLocation'];
        $photo = MsGraph::get('me/photos/120x120/$value');

        $mobile = preg_replace('/\D+/', '', $mobile);
        $mobile = str_replace(' ', '', $mobile);
        if(substr($mobile, 0, 2) == 62){
            $mobile = preg_replace( '/(\+?\d{2})(\d{7,8})/', '0$2', $mobile);
        }else if(substr($mobile, 0, 1) == 0){
            $mobile = preg_replace( '/(0)(\d{7,8})/', '0$2', $mobile);
        }else{
            $mobile = "0".$mobile;
        }
        $user = User::updateOrCreate(
            ['email'=>$event->token['info']['mail']],
            [
                'name'     => $name,
                'title'    => $title,
                'mobile'   => $mobile,
                'email'    => $email,
                'location' => $location,
                'photo'    => base64_encode($photo),
            ]
        );
        $tokenId        = $event->token['token_id'];
        $token          = MsGraphToken::findOrFail($tokenId);
        $token->user_id = $user->id;
        $token->email   = $user->email;
        $token->save();

        Auth::login($user);
    }
}
