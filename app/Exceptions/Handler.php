<?php

namespace App\Exceptions;

use App\Libs\ImageURL;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Laravel\Socialite\Two\InvalidStateException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($this->isHttpException($exception)){
            $folder = \Lib::module_config('folder_name');

            switch ($exception->getStatusCode()) {
                case 404:
                    $fullUrl = $request->fullUrl();
                    if(str_contains($fullUrl, '/'.\ImageURL::DEFAULT_DIR.'/') && str_contains($fullUrl, '/'.\ImageURL::DEFAULT_DIR_THUMB)){

                        $image = ImageURL::autoGenImageFromURL($request->fullUrl());
                        return $image->response();
                    }
                    return response()->view($folder.'::404', [], 404);
                case 500:
                    return response()->view($folder.'::500', [], 500);
            }
        }elseif ($exception instanceof AuthorizationException) {
            $folder = \Lib::module_config('folder_name');
            return response()->view($folder.'::403', [], 403);
        }elseif($exception instanceof ClientException || $exception instanceof InvalidStateException){
            $folder = \Lib::module_config('folder_name');
            return response()->view($folder.'::auth.social_fail', ['msg' => $exception->getMessage(),'site_title'=>'Đăng nhập thất bại'], 400);
        }
        return parent::render($request, $exception);
    }
}
