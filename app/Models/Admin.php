<?php

namespace App\Models;

use App\Models\AdminProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Admin extends BaseAuthenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'is_superadmin',
        'admin_profile_id',
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
        'is_superadmin' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'is_superadmin' => false,
    ];

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'admin_profile_id' => [
                'required', 'numeric',
                function ($attributes, $value, $fail) {
                    $exists = AdminProfile::where([
                        ['id', $value]
                    ])->exists();

                    if (!$exists) {
                        return $fail(__('api.general.invalid_profile'));
                    }
                }
            ],

            'username' => 'required|unique:admin,username,[id],id|max:20',
            'password' => 'required|min:6|max:255',
            'name'     => 'required|max:255',
        ];

        $this->includable = [
            'admin_profile' => ['single', 'App\Resources\Admin\AdminProfile']
        ];

        $this->filterable = [
            'username', 'name', 'admin_profile_id', 'is_superadmin', 'status', 'created_at'
        ];

        $this->equalable = [
            'admin_profile_id', 'status'
        ];
    }

    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof Admin && !$authuser->is_superadmin) {
            $builder->where('is_superadmin', false);
        }
    }

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

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'admin_module', 'admin_id', 'module_id')
            ->withPivot('status');
    }

    public function admin_profile()
    {
        return $this->belongsTo(AdminProfile::class, 'admin_profile_id');
    }

    public function getAllowedModuleKeys()
    {
        $allModules = [];
        $adminProfileModules = $this->admin_profile->modules;

        foreach ($adminProfileModules as $pm) {
            $allModules[$pm->id] = $pm;
        }

        $adminModule = $this->modules;

        // iterate through pivot table to add/remove module
        foreach ($adminModule as $adminModule) {
            $moduleId = $adminModule->id;

            if (
                $adminModule->pivot->status == 'add'
                && !isset($allModules[$moduleId])
            ) {
                $allModules[$moduleId] = $adminModule;
            } else if (
                $adminModule->pivot->status == 'remove'
                && isset($allModules[$moduleId])
            ) {
                unset($allModules[$moduleId]);
            }
        }

        $allowedModules = array_column($allModules, 'modulekey');

        return $allowedModules;
    }
}
