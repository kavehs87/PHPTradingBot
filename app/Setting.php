<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/14/18
 * Time: 19:52
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];


    public static function getValue($key)
    {
        $record = self::where('key', $key)->first();
        if (isset($record) && !empty($record)) {
            return json_decode($record->value,true);
        }
        return false;
    }

    public static function setValue($key, $value)
    {
        $exists = self::getValue($key);
        if ($exists) {
            $record = self::where('key', $key)->first();
            $record->value = json_encode($value);
            return $record->save();
        } else {
            return self::create([
                'key' => $key,
                'value' => json_encode($value)
            ]);
        }
    }

}