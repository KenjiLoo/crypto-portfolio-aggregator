<?php

namespace App\Models;

use App\Jobs\ProcessSiteGroupJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class SiteGroup extends BaseModel
{
    use HasFactory;

    /**
     * The model table name.
     *
     * @var array
     */
    protected $table = 'site_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'company_code',
        'status',
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

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'name' => 'required|max:255',
            'company_code' => 'required|max:30'
        ];

        $this->filterable = [
            'name',
            'company_code',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * This method is the post event after a record is saved into the database.
     *
     * @param  Request  $request  The request object.
     *
     * @return void
     */
    public function onAfterSave(Request $request, $custom = [])
    {
        ProcessSiteGroupJob::dispatchIf($this->isStoring, $this->id);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_id');
    }
}
