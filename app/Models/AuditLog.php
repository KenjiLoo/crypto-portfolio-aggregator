<?php

namespace App\Models;

class AuditLog extends BaseModel
{
    protected $table = 'audit_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'module', 'action', 'url', 'ip', 'request', 'status', 'response',
        'user_type', 'user_id', 'username',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->filterable = [
            'module', 'action', 'url', 'ip', 'status', 'username',
        ];
    }
}
