<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DomainUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $domain;
    public $tenant;
    public $domainUrl;
    public $isFallback;

    public function __construct($domain, $tenant, $isFallback = false)
    {
        $this->domain = $domain;
        $this->tenant = $tenant;
        $this->isFallback = $isFallback;
        
        // Build the complete URL
        $this->domainUrl = (app()->isLocal() ? 'http://' . $domain. ':8000' : 'https://' . $domain);
        
        // No need to fetch users here since we're getting them before sending
    }

    public function build()
    {
        $subject = "New Domain Added: {$this->domain}";
        
        if ($this->isFallback) {
            $subject = "[System Admin] " . $subject;
        }

        return $this->subject($subject)
                   ->view('emails.domain_updated')
                   ->with([
                       'domainUrl' => $this->domainUrl,
                       'tenant' => $this->tenant,
                       'isFallback' => $this->isFallback
                   ]);
    }
}