<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminProfileModule extends BaseModel
{
    use HasFactory;

    protected $table = 'admin_profile_module';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_profile_id',
        'module_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'admin_profile_id' => 'required|integer',
            'module_id' => 'required|integer'
        ];

        $this->filterable = [
            'admin_profile_id', 'module_id'
        ];
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function admin_profile()
    {
        return $this->belongsTo(AdminProfile::class);
    }
}
