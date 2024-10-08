<?php

namespace Bikaraan\BCore\Config;

use Bikaraan\BCore\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfigModel extends Model
{
    use DefaultDatetimeFormat;

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('bcore.database.connection') ?: config('database.default'));

        $this->setTable(config('bcore.extensions.config.table', 'admin_config'));
    }

    /**
     * On mode boot
     * Use as observer
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::saved(function ($model) {
            Cache::forget(Config::CACHE_KEY_DATA);
        });
    }

    /**
     * Set the config's value.
     *
     * @param string|null $value
     */
    public function setValueAttribute($value = null)
    {
        if (config('bcore.extensions.config.valueEmptyStringAllowed', false)) {
            $this->attributes['value'] = is_null($value) ? '' : $value;
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
