<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends BaseModel
{
    use HasFactory;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'site_group_id',
        'name',
        'site_code',
        'company_code',
        'currency',
        'usd_rate',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'usd_rate' => 'float',
    ];

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'site_group_id' => 'required|integer',
            'name' => 'required|max:255',
            'site_code' => 'required|unique:site,site_code,[id],id|max:50',
            'company_code' => 'required|max:30',
            'currency' => 'required|max:5',
            'usd_rate' => 'required|numeric',
        ];

        $this->includable = [
            'site_group_admins' => ['collection', 'App\Resources\SiteGroup\SiteGroupAdmin'],
            'site_group' => ['single', 'App\Resources\Admin\SiteGroup'],
        ];

        $this->filterable = [
            'site_group_id',
            'name',
            'site_code',
            'company_code',
            'currency',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Use Model::apply() to always append site_group_id = current user site_group_id in query
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof SiteGroupAdmin) {
            $builder->where('site_group_id', $authuser->site_group_id);
        }
    }

    /**
     * Get the site group that create the site.
     * 
     * @return SiteGroup
     */
    public  function site_group()
    {
        return $this->belongsTo(SiteGroup::class, 'site_group_id');
    }

    public function site_group_admins()
    {
        return $this->belongsToMany(SiteGroupAdmin::class, 'site_group_admin_site', 'site_group_admin_id', 'site_id');
    }
}
