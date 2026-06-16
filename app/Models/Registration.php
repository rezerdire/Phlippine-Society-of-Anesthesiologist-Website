<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'registrations';

    protected $fillable = [
        'psa_id',
        'prc_number',
        'last_name',
        'first_name',
        'middle_name',
        'hospital_name',
        'hospital_address',
        'email',
        'contact_number',
        'gender',
        'membership',
        'discount_id',
        'proof_payment',
        'status',
        'country',
    ];

    protected $casts = [
        'prc_number' => 'integer',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    // Helpers
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function hasDiscount(): bool
    {
        return !is_null($this->discount_id);
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }
}