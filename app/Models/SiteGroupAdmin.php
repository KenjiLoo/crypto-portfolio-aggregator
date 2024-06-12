<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class SiteGroupAdmin extends BaseAuthenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site_group_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'site_group_id',
        'site_profile_id',
        'username',
        'name',
        'is_master_account',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'site_group_id' => 'integer',
        'site_profile_id' => 'integer',
        'is_master_account' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'site_profile_id' => 'required|integer',
            'username' => 'required|unique:site_group_admin,username,[id],id|max:20',
            'password' => 'required|min:6|max:255',
            'name' => 'required|max:255',
            'is_master_account' => 'boolean',
            'status' => 'required|max:10',
        ];

        $this->includable = [
            'sites' => ['collection', 'App\Resources\Admin\Site'],
            'site_group' => ['single', 'App\Resources\Admin\SiteGroup'],
            'site_profile' => ['single', 'App\Resources\SiteGroup\SiteProfile'],
        ];

        $this->filterable = [
            'site_group_id',
            'site_profile_id',
            'username',
            'name',
            'is_master_account',
            'status',
            'created_at',
            'updated_at'
        ];

        $this->equalable = [
            'site_group_id',
            'site_profile_id',
            'status'
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

            if (!$authuser->is_master_account) {
                $builder->where('is_master_account', false);
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
     * Updates the tracking tag affiliate pivot table after assigning affiliate ids to tracking tag during creation/updated
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function onAfterSave(Request $request, $custom = [])
    {
        $siteIds = json_decode($request->input('site_ids', ''), true);

        if (is_array($siteIds)) {
            if (count($siteIds) === 1 && empty($siteIds[0])) {
                $authuser = $request->user();

                if ($authuser instanceof SiteGroupAdmin) {
                    $siteIds = Site::where('site_group_id', $authuser->site_group_id)->pluck('id')->toArray();
                }
            }

            $this->sites()->sync($siteIds);
        }
    }

    /**
     * Fill in the data for the specifc field before save to database
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function fillFromRequest(Request $request, $data = null)
    {
        if (!$data) {
            $data = parse_request_data($request->all());
        }

        if (isset($data['password'])) {
            $this->password = Hash::make($data['password']);
        }

        $this->fill($data);
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
     * Get the site that belong to site group admin.
     *
     * @return Site
     */
    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_group_admin_site', 'site_group_admin_id', 'site_id');
    }

    /**
     * Get the site profile that create the site group admin.
     *
     * @return SiteProfile
     */
    public function site_profile()
    {
        return $this->belongsTo(SiteProfile::class, 'site_profile_id');
    }

    /**
     * Get the modules that belong to the site group with active status.
     *
     * @return Module
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'admin_module', 'admin_id', 'module_id')
            ->withPivot('status');
    }

    /**
     * Returns a list of menus belonging to the site group admin.
     *
     * @return Array
     */
    public function getAllowedModuleKeys()
    {
        $allModules = [];
        $profileModules = $this->site_profile->modules;

        foreach ($profileModules as $pm) {
            $allModules[$pm->id] = $pm;
        }

        $allowedModules = array_column($allModules, 'modulekey');

        return $allowedModules;
    }

    /**
     * Returns TRUE if site group is ACTIVE, otherwise FALSE.
     *
     * @return Boolean
     */
    public function isSiteGroupActive()
    {
        return $this->site_group->status === self::STATUS_ACTIVE;
    }

    /**
     * Returns TRUE if sites are ACTIVE, otherwise FALSE.
     *
     * @return Boolean
     */
    public function isSiteActive()
    {
        foreach ($this->sites as $site) {
            if ($site->status === Site::STATUS_INACTIVE) {
                return false;
            }
        }

        return true;
    }
}
