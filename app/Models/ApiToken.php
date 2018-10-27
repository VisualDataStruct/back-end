<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiToken
 * @package App\Models
 *
 * @property integer $id
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expired_at
 *
 * @property-read boolean $isExpired
 * @property-read User $user
 */
class ApiToken extends Model
{
    protected $table = 'api_token';

    protected $dates = ['created_at', 'updated_at', 'expired_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expired_at->timestamp < Carbon::now()->timestamp;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * @return null
     */
    public function addTime()
    {
        if ($this->expired_at->timestamp < $this->updated_at->addHour()) {
            $this->expired_at = Carbon::now()->addHour();
        } else {
            $this->expired_at = Carbon::now()->addMonth();
        }
        $this->save();
        return;
    }
}
