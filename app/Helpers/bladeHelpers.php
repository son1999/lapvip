<?php
/**
 * Created by PhpStorm.
 * User: Tannv
 * Date: 2019-06-30
 * Time: 09:48
 */
if (! function_exists('old_blade')) {

    function old_blade($key = null, $default = null)
    {
        $default = empty($default) ? config('helper.data_edit') : $default;
        return app('request')->old($key, optional($default)->$key);
    }
}
if (! function_exists('set_old')) {

    function set_old($data = null)
    {
        $data->editMode = true;
        config(['helper.data_edit' => $data]);
    }
}
