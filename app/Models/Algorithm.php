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
 * @property array $CPlusCode
 * @property string $blocksJson
 * @property string $blocksXml
 * @property array $problems
 * @property boolean $passed
 * @property string $tagName
 * @property boolean $isPassed
 * @property array $initVar
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
    public function setTagNameAttribute($value)
    {
        $this->attributes['tagName'] = clean($value, PurifierConfig::noAutoParagraph());
    }
    public function setBlocksJsonAttribute($value)
    {
        $this->attributes['blocksJson'] = $value;
    }
    public function getBlocksJsonAttribute()
    {
        return $this->attributes['blocksJson'];
    }
    public function setInitVarAttribute($value)
    {
        $this->attributes['initVar'] = json_encode($value);
    }
    public function getInitVarAttribute()
    {
        return json_decode($this->attributes['initVar']) ?? [$this->attributes['initVar']];
    }
    public function setCPlusCodeAttribute($value)
    {
        $this->attributes['CPlusCode'] = json_encode($value);
    }
    public function getCPlusCodeAttribute()
    {
        return json_decode($this->attributes['CPlusCode']) ?? [$this->attributes['CPlusCode']];
    }
    public function setBlocksXmlAttribute($value)
    {
        $this->attributes['blocksXml'] = $value;
    }
    public function getBlocksXmlAttribute()
    {
        return $this->attributes['blocksXml'];
    }
    public function setProblemsAttribute($value)
    {
        $this->attributes['problems'] = json_encode($value);
    }
    public function getProblemsAttribute()
    {
        return json_decode($this->attributes['problems']) ?? [$this->attributes['problems']];
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
            'tagName' => $this->tagName,
            'classification_id' => $this->classification_id,
            'name' => $this->name,
            'initVar' => $this->initVar,
            'passed' => $this->passed,
            'deleted_at' => $this->deleted_at->timestamp ?? null,
        ];
        switch ($type) {
            case 'list':
                break;
            case 'detail':
                $data['blocksXml'] = $this->blocksXml;
                $data['CPlusCode'] = $this->CPlusCode;
                $data['blocksJson'] = $this->blocksJson;
                $data['problem'] = $this->problems;
                break;
        }
        return $data;
    }
}
