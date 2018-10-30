<?php

namespace App\Models;

use App\Config\PruifierConfig;
use App\Config\PurifierConfig;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel as Model;

/**
 * Class Classification
 * @package App\Models
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $sum
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Algorithm[] $algorithms
 */
class Classification extends Model
{

    use SoftDeletes;

    protected $table = 'classification';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setDescriptionAttribute(string $value)
    {
        $this->attributes['description'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function algorithms()
    {
        return $this->hasMany('App\Models\Algorithm', 'classification_id', 'id');
    }
}
