<?php

namespace App\Models;

use App\Config\PurifierConfig;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel as Model;

/**
 * Class Algorithm
 * @package App\Models
 *
 * @property integer $id
 * @property integer $classification_id
 * @property string $name
 * @property array $pseudoCode
 * @property array $CPlusCode
 * @property array $jsCode
 * @property array $explain
 * @property array $problems
 * @property boolean $passed
 * @property boolean $isPassed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Classification $classification
 */
class Algorithm extends Model
{

    use SoftDeletes;

    protected $table = 'algorithm';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->classification_id = 0;
        $this->CPlusCode = [];
        $this->problems = [];
        $this->attributes['passed'] = 0;
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setPseudoCodeAttribute($value)
    {
        $this->attributes['pseudoCode'] = json_encode($value);
    }
    public function getPseudoCodeAttribute()
    {
        try {
            return json_decode($this->attributes['pseudoCode']);
        } catch (\Throwable $exception) {
            return [$this->attributes['pseudoCode']];
        }
    }
    public function setCPlusCodeAttribute($value)
    {
        $this->attributes['CPlusCode'] = json_encode($value);
    }
    public function getCPlusCodeAttribute()
    {
        try {
            return json_decode($this->attributes['CPlusCode']);
        } catch (\Throwable $exception) {
            return [$this->attributes['CPlusCode']];
        }
    }
    public function setJsCodeAttribute($value)
    {
        $this->attributes['jsCode'] = json_encode($value);
    }
    public function getJsCodeAttribute()
    {
        try {
            return json_decode($this->attributes['jsCode']);
        } catch (\Throwable $exception) {
            return [$this->attributes['jsCode']];
        }
    }
    public function setExplainAttribute($value)
    {
        $this->attributes['explain'] = json_encode($value);
    }
    public function getExplainAttribute()
    {
        try {
            return json_decode($this->attributes['explain']);
        } catch (\Throwable $exception) {
            return [$this->attributes['explain']];
        }
    }
    public function setProblemsAttribute($value)
    {
        $this->attributes['problems'] = json_encode($value);
    }
    public function getProblemsAttribute()
    {
        try {
            return json_decode($this->attributes['problems']);
        } catch (\Throwable $exception) {
            return [$this->attributes['problems']];
        }
    }
    public function getIsPassedAttribute()
    {
        return $this->passed === 1;
    }
    public function classification()
    {
        return $this->belongsTo('App\Models\Classification', 'classification_id', 'id');
    }

    /**
     * @param string $name
     * @param string $link
     * @return null
     */
    public function addProblem(string $name, string $link)
    {
        $problems = $this->problems;
        $problems[] = [
            'name' => $name,
            'link' => $link,
        ];
        $this->problems = $problems;
        return;
    }
    public function deleteProblem(string $name, string $link = '')
    {
        $problems = $this->problems;
        foreach ($problems as $key => $problem) {
            if ($link === '') {
                if ($problem->name === $name) {
                    unset($problems[$key]);
                }
            } else {
                if ($problem->name === $name && $problem->link === $link) {
                    unset($problems[$key]);
                }
            }
        }
        $problems = array_values($problems);
        $this->problems = $problems;
        return;
    }

    /**
     * @return null
     */
    public function pass()
    {
        $this->attributes['passed'] = 1;
        return;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getData(string $type)
    {
        $data = [
            'id' => $this->id,
            'classification_id' => $this->classification_id,
            'name' => $this->name,
            'deleted_at' => $this->deleted_at->timestamp ?? null,
        ];
        switch ($type) {
            case 'list':
                break;
            case 'detail':
                $data['pseudoCode'] = $this->pseudoCode;
                $data['CPlusCode'] = $this->CPlusCode;
                $data['jsCode'] = $this->jsCode;
                $data['explain'] = $this->explain;
                $data['problem'] = $this->problems;
                break;
        }
        return $data;
    }
}
