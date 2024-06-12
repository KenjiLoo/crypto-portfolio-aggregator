<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Module extends BaseModel
{
    use HasApiTokens, HasFactory;

    protected $table = 'module';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'master_id',
        'type',
        'modulekey',
        'name',
        'sequence',
        'icon',
        'route',
        'is_superadmin',
        'is_master',
        'is_hidden'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'master_id' => 'integer',
        'sequence' => 'integer',
        'is_superadmin' => 'boolean',
        'is_master' => 'boolean',
        'is_hidden' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'is_superadmin' => false,
        'is_master' => false,
        'is_hidden' => false,
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'type' => 'required|in:admin,site_group',
            'modulekey' => ['required', 'max:20', function ($attribute, $value, $fail) {
                if (!empty(request()->id)) {
                    $exists = Module::where([
                        ['modulekey', $value],
                        ['type', request()->type],
                        ['id', '!=', request()->id]
                    ])->exists();

                    if ($exists) {
                        return $fail(__('api.admin.invalid_module_key'));
                    }
                } else {
                    $exists = Module::where([
                        ['modulekey', $value],
                        ['type', request()->type]
                    ])->exists();

                    if ($exists) {
                        return $fail(__('api.admin.invalid_module_key'));
                    }
                }
            }],
            'name' => 'required|min:3|max:20',
            'sequence' => 'required|numeric|between:0,255',
            'master_id' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if ($value != 0) {
                    $exists = Module::where([
                        ['id', $value],
                        ['type', request()->type],
                        ['is_master', true]
                    ])->exists();

                    if (!$exists) {
                        return $fail(__('api.admin.invalid_module_key'));
                    }
                }
            }],
            'is_superadmin' => 'required|boolean',
            'is_master' => ['required', 'boolean', function ($attribute, $value, $fail) {
                    if (request()->master_id != '0' && $value) {
                        return $fail(__('api.admin.invalid_master'));
                    }
                }
            ],
            'is_hidden' => 'required|boolean'
        ];

        $this->includable = [
            'master' => ['single', 'App\Resources\Admin\Module']
        ];

        $this->filterable = [
            'type', 'modulekey', 'name', 'sequence', 'master_id', 'is_superadmin', 'is_master', 'is_hidden', 'created_at', 'updated_at'
        ];
    }

    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof Admin && !$authuser->is_superadmin) {
            $builder->where('is_superadmin', false);
        }
    }

    public function admin()
    {
        return $this->belongsToMany(Admin::class, 'admin_module', 'admin_id', 'module_id')
            ->withPivot('status');
    }

    public function master()
    {
        return $this->belongsTo(Module::class, 'master_id');
    }

    public function admin_profile()
    {
        return $this->belongsToMany(AdminProfile::class, 'admin_profile_module', 'admin_profile_id', 'module_id');
    }
}
