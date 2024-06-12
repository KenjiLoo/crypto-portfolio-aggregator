<?php

namespace App\Models;

use Illuminate\Http\Request;

class AdminProfile extends BaseModel
{
    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'admin_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'is_superadmin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_superadmin' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'is_superadmin' => false,
    ];

    protected $moduleIds = [];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'name' => 'required|max:255',
            'is_superadmin' => 'required|boolean',
            'module_ids' => 'required|array'
        ];

        $this->includable = [
            'modules' => ['collection', 'App\Resources\Admin\Module']
        ];

        $this->filterable = [
            'name', 'is_superadmin', 'created_at', 'updated_at'
        ];
    }

    /**
     * Apply a pre-filter condition to the builder query.
     *
     * @param Object $builder The builder query object.
     *
     * @return void
     */
    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof Admin && !$authuser->is_superadmin) {
            $builder->where('is_superadmin', false);
        }
    }

    /**
     * Updates the profile_module pivot table after assigning module ids to profile during creation/updated
     *
     * @param Request $request The request object.
     *
     * @return void
     */
    public function onAfterSave(Request $request, $custom = [])
    {
        $moduleIds = $request->input('module_ids', []);
        $this->modules()->sync($moduleIds);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'admin_profile_module', 'admin_profile_id', 'module_id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
