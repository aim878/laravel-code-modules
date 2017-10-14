<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InputFieldData extends Model
{

    protected $table = "input_fields_data";
    protected $fillable = ['refrence_id'];
    public $timestamps = true;

    public function field(){
        return $this->belongsTo('App\Model\InputField');
    }
}
