<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseAuthenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
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
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
    ];

    public function __construct()
    {
        parent::__construct();

        $this->rules = [
            'username' => 'required|unique:user,username,[id],id|max:20',
            'password' => 'required|min:6|max:255',
            'name'     => 'required|max:255',
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
        // $authuser = request()->user();
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

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'user_id', 'id');
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class, 'user_id', 'id');
    }
}
