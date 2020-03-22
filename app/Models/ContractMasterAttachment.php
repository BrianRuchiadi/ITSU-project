<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractMasterAttachment extends Model
{
    use SoftDeletes;
    
    public $table = 'contractmasterattachment';
    protected $fillable = [
        'contractmast_id',
        'icno_file',
        'icno_mime',
        'icno_size',
        'income_file',
        'income_mime',
        'income_size',
        'bankstatement_file',
        'bankstatement_mime',
        'bankstatement_size',
        'comp_form_file',
        'comp_form_mime',
        'comp_form_size',
        'comp_icno_file',
        'comp_icno_mime',
        'comp_icno_size',
        'comp_bankstatement_file',
        'comp_bankstatement_mime',
        'comp_bankstatement_size'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
