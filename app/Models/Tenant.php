<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public function users()
    {
        return $this->hasMany(TenantUser::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function firstUser()
{
    return $this->users()->oldest()->first();
    // or use 'created_at' if you have custom sorting:
    // return $this->users()->orderBy('created_at')->first();
}
}
