<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteGroupAdminSite extends BaseModel
{
    use HasFactory;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site_group_admin_site';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'site_group_admin_id',
        'site_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'site_group_admin_id' => 'integer',
        'site_id' => 'integer',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'site_group_admin_id' => 'required|integer',
            'site_id' => 'required|integer',
        ];

        $this->filterable = [
            'site_group_admin_id', 
            'site_id'
        ];
    }

    /**
     * Get the site group admin that create the site.
     * 
     * @return SiteGroupAdmin
     */
    public function site_group_admin()
    {
        return $this->belongsTo(SiteGroupAdmin::class, 'site_group_admin_id');
    }

    /**
     * Get the sites for the site group admin.
     * 
     * @return Module
     */
    public function sites()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
