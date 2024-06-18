<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Watchlist extends BaseModel
{
    use HasFactory;

    protected $table = 'watchlist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'crypto_id',
        'crypto_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'crypto_id' => 'required|integer',
            'crypto_name' => 'required|max:255',
        ];

        $this->includable = [
        ];

        $this->filterable = [
            'username', 'name'
        ];

        $this->equalable = [
        ];
    }

    public function apply($builder, $custom = [])
    {
        $authuser = request()->user();

        if ($authuser instanceof User) {
            $builder->where('user_id', $authuser->id);
        }
    }

    public function onBeforeSave(Request $request, $custom = [])
    {
        $authuser = request()->user();
        $this->user_id = $authuser->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
