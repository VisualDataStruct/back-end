<?php

namespace App\Models;

use App\Config\PurifierConfig;
use App\Helper;
use Carbon\Carbon;
use App\Models\BaseModel as Model;

/**
 * Class Verify
 * @package App\Models
 *
 * @property integer $id
 * @property string $email
 * @property string $verify_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expired_at
 *
 * @property-read boolean $isExpired
 * @property-read User $user
 */
class Verify extends Model
{
    protected $table = 'api_token';

    protected $dates = ['created_at', 'updated_at', 'expired_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['verify_code'] = Helper::generateVerifyCode(6, '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM');
        $this->expired_at = Carbon::now()->addMinutes(15);
    }

    public function setEmailAttribute($value)
    {
        return $this->attributes['email'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function getIsExpiredAttribute()
    {
        return $this->expired_at->timestamp < Carbon::now()->timestamp;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'email', 'email');
    }
}
