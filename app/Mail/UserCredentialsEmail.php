<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCredentialsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $email;
    public $password;
    public $role;


    public function __construct($username, $email, $password, $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function build()
    {
        return $this->view('emails.user_credentials')->with([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
        ]);
    }
}