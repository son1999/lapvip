<?php
/**
 * Created by PhpStorm.
 * Filename: LoadDynamicRouter.php
 * User: Thang Nguyen Nhan
 * Date: 07-Jun-19
 * Time: 16:24
 */
namespace App\Libs;

use Symfony\Component\Finder\Finder;

class LoadDynamicRouter {
    static function loadRoutesFrom($module = ''){
        $files = Finder::create()
            ->in(app_path('Modules/'.$module))
            ->name('routes.php');

        self::require($files);
    }

    protected static function require($files)
    {
        foreach($files as $file)
            require $file->getRealPath();
    }
}