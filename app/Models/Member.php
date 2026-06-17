<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $primaryKey = 'member_id_no';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'member_id_no',
        'psa_chapter_code',
        'psa_mem_type',
        'mem_last_name',
        'mem_first_name',
        'mem_middle_name',
        'mem_email_address',
        'mem_gender',
        'bal',
    ];

    protected $hidden = [
        'password',
    ];



    public function registration()
{
    return $this->hasOne(\App\Models\Registration::class, 'psa_id', 'member_id_no');
}
}
