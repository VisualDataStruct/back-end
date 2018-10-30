<?php

namespace App\Models;

use App\Config\PurifierConfig;
use Carbon\Carbon;
use PascalDeVink\ShortUuid\ShortUuid;
use App\Models\BaseModel as Model;

/**
 * Class User
 * @package App\Models
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $realName
 * @property string $email
 * @property string $github
 * @property string $phone
 * @property integer $contribution
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read boolean $isAdmin
 */
class User extends Model
{
    protected $table = 'user';

    protected $keyType = 'string';

    protected $dates = ['created_at', 'updated_at'];

    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['id'] = ShortUuid::uuid4();
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = app('hash')->make($value);
    }
    public function setRealNameAttribute($value)
    {
        $this->attributes['realName'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setGithubAttribute($value)
    {
        $this->attributes['github'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function getIsAdminAttribute()
    {
        return $this->attributes['id'] === '1';
    }
    public function checkPassword($password)
    {
        return app('hash')->check($password, $this->password);
    }
}
