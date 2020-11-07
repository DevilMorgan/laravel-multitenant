<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {

        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if(session()->has('tenant_id')){
                $model->tenant_id = session()->get('tenant_id');
            }
        });
    }

    protected function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}