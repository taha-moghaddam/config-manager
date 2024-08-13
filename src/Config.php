<?php

namespace Bikaraan\BCore\Config;

use Bikaraan\BCore\Facades\Admin;
use Bikaraan\BCore\Extension;
use Illuminate\Support\Facades\Cache;

class Config extends Extension
{
    const CACHE_KEY_DATA = 'admin_config.name_value';

    /**
     * Load configure into laravel from database.
     *
     * @return void
     */
    public static function load()
    {
        $configData = Cache::remember(self::CACHE_KEY_DATA, 60, function () {
            return ConfigModel::all(['name', 'value']);
        });

        foreach ($configData as $config) {
            config([$config['name'] => $config['value']]);
        }
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('config', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource(
                config('bcore.extensions.config.name', 'config'),
                config('bcore.extensions.config.controller', 'Bikaraan\BCore\Config\ConfigController')
            );
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Config', 'config', 'fa-toggle-on');

        parent::createPermission('Admin Config', 'ext.config', 'config*');
    }
}
