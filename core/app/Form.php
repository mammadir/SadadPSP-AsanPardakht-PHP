<?php

namespace App;

class Form extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fp_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'amount',
        'pay_limit',
        'pay_count',
        'fields',
        'image',
        'default',
        'status',
        'form_size'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fields' => 'array',
    ];

    public static $status = [
        'active' => 1,
        'deleted' => 2
    ];
}
