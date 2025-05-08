<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $randomPassword;
    public $domain;        // Just the domain name (e.g., "donmaca.com.unidorm.localhost")
    public $domainUrl;     // Full URL with protocol and port if needed
    public $subscriptionPlan;

    public function __construct($userName, $userEmail, $randomPassword, $domain, $subscriptionPlan)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->randomPassword = $randomPassword;
        $this->domain = $domain;
        $this->subscriptionPlan = $subscriptionPlan;
        
        $this->domainUrl = (app()->isLocal() ? 'http://' . $domain : 'https://' . $domain);

    }

    public function build()
    {
        return $this->view('emails.tenant_created')
                    ->with([
                        'userName' => $this->userName,
                        'userEmail' => $this->userEmail,
                        'randomPassword' => $this->randomPassword,
                        'domain' => $this->domain,
                        'domainUrl' => $this->domainUrl, // Pass the full URL to the view
                        'subscriptionPlan' => $this->subscriptionPlan,
                    ]);
    }
}