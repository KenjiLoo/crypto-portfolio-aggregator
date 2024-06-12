<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class SiteProfile extends BaseModel
{
    use HasFactory;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'site_group_id',
        'is_superadmin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'site_group_id' => 'integer',
        'is_superadmin' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'name' => 'required|max:255',
            'is_superadmin' => 'boolean',
        ];

        $this->includable = [
            'site_group' => ['single', 'App\Resources\Admin\SiteGroup'],
            'modules' => ['collection', 'App\Resources\Admin\Module']
        ];

        $this->filterable = [
            'name',
            'site_group_id',
            'is_superadmin',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Use Model::apply() to always append site_group_id = current user site_group_id in query
     */
    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof SiteGroupAdmin) {
            $builder->where('site_group_id', $authuser->site_group_id);

            if (!$authuser->is_master_account) {
                $builder->where('is_superadmin', false);
            }
        }
    }

    /**
     * Assign site_group_id to current auth user’s site_group_id before creating
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function onBeforeSave(Request $request, $custom = [])
    {
        $authuser = $request->user();

        if ($authuser instanceof SiteGroupAdmin && empty($this->site_group_id)) {
            $this->site_group_id = $authuser->site_group_id;
        }
    }

    /**
     * Updates the profile_module pivot table after assigning module ids to profile during creation/updated
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function onAfterSave(Request $request, $custom = [])
    {
        $moduleIds = $request->input('module_ids', []);
        $this->modules()->sync($moduleIds);
    }

    /**
     * Get the site group that create the site group admin.
     *
     * @return SiteGroup
     */
    public function site_group()
    {
        return $this->belongsTo(SiteGroup::class, 'site_group_id');
    }

    /**
     * Get the modules for the site profile.
     *
     * @return Module
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'site_profile_module', 'site_profile_id', 'module_id');
    }
}
