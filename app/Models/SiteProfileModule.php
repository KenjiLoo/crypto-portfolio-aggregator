<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteProfileModule extends BaseModel
{
    use HasFactory;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site_profile_module';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'site_profile_id',
        'module_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'site_profile_id' => 'integer',
        'module_id' => 'integer',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'site_profile_id' => 'required|integer',
            'module_id' => 'required|integer',
        ];

        $this->filterable = [
            'site_profile_id', 'module_id'
        ];
    }

    /**
     * Get the site profile that create the site profile module.
     * 
     * @return SiteProfile
     */
    public function site_profile()
    {
        return $this->belongsTo(SiteProfile::class, 'site_profile_id');
    }

    /**
     * Get the module for the site profile module.
     * 
     * @return Module
     */
    public function modules()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
