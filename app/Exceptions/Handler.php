<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use stdClass;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });


        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 404,
                        'data' => new stdClass(),
                        'message' => __('Resource model not found'),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    __('Resource model not found'),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    404

                );
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                $erorList = $e->errors();
                $errorKeys = array_keys($erorList);
                $erorListMod = array_map(
                    function ($value, $key) {
                        return ['field' => $key, 'erorrs' => $value];
                    },
                    $erorList,
                    $errorKeys
                );

                return response()->json(
                    [
                        'status' => 422,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => $erorListMod,
                        'meta' => $e->getTrace(),
                        'info' => [],
                    ],
                    422
                );
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 401,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    config('app.env') == 'production'
                                        ? __('User is not authenticated')
                                        : $e->getMessage(),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    401

                );
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 403,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    config('app.env') == 'production'
                                        ? __('Have not enough permission')
                                        : $e->getMessage(),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    403

                );
            }
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    config('app.env') == 'production'
                                        ? __('Something wrong happened')
                                        : $e->getMessage(),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    500

                );
            }
        });
    }
}
