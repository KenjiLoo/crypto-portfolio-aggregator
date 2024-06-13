<?php

namespace App\Models;

use Illuminate\Http\Request;

class Portfolio extends BaseModel
{
    use HasFactory;

    protected $table = 'portfolio';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'crypto_id',
        'crypto_name',
        'unit',
        'buy_price'
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
            'user_id' => ['required', 'interger'],
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
}
