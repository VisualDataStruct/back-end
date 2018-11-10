<?php

namespace App\Models;

use App\Config\PurifierConfig;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use PascalDeVink\ShortUuid\ShortUuid;
use App\Models\BaseModel as Model;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

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
 * @property-read ApiToken[] $apiTokens
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

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
    public function apiTokens()
    {
        return $this->hasMany('\App\Models\ApiToken', 'user_id', 'id');
    }
    /**
     * @param $password
     * @return boolean
     */
    public function checkPassword($password)
    {
        return app('hash')->check($password, $this->password);
    }

    /**
     * @param string $type
     * @return array
     */
    public function getData(string $type)
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'github' => $this->github,
            'contribution' => $this->contribution,
        ];
        switch ($type) {
            case 'list':
                break;
            case 'detail':
                $data['realName'] = $this->realName;
                $data['phone'] = $this->phone;
                break;
            default:
                break;
        }
        return $data;
    }
}
