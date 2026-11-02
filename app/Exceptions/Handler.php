<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    }

    /**
     * A 419 (TokenMismatchException) means the form's CSRF token no longer
     * matches the session — almost always because the page sat open long
     * enough for the session to expire. Rather than showing Laravel's bare
     * "419 | Page Expired" page, send the user back to the form they were
     * on with their input restored (except secrets) and a plain-language
     * message, so they can just resubmit.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return $this->redirectForExpiredSession($request);
        }

        return parent::render($request, $e);
    }

    private function redirectForExpiredSession(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your session expired, please try again.',
            ], 419);
        }

        return redirect()
            ->back()
            ->withInput($request->except($this->dontFlash))
            ->with('error', 'Your session expired, please try again.');
    }
}
