<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminModule extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin_module';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_id',
        'module_id',
        'status'
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
            'admin_id' => 'required|integer',
            'module_id' => 'required|integer',
            'status' => 'nullable|max:20'
        ];

        $this->includable = [
            'module' => ['collection', 'App\Resources\Module\Module']
        ];

        $this->filterable = [
            'admin_id', 'module_id', 'status'
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
