<?php

namespace App\Exceptions;

use App\Traits\ResponceTrait;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;

class Handler extends ExceptionHandler
{
    use ResponceTrait;
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
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
                return abort('404');
            }

    
            // Default to the parent class' implementation of handler
            return parent::render($request, $exception);
        // If the request wants JSON (AJAX doesn't always want JSON)
            

        if ($request->wantsJson()) {
            // Define the response
            $response = [
                'errors' => 'Sorry, something went wrong.'
            ];

            // If the app is in debug mode
            if (config('app.debug')) {
                // Add the exception class name, message and stack trace to response
                $response['exception'] = get_class($exception); // Reflection might be better here
                $response['message'] = $exception->getMessage();
                $response['trace'] = $exception->getTrace();
            }
            $response['code']=0;
            $response['error']=true;
            $response['message']=$exception->getMessage();

            // Default response of 400
            $status = 400;

            // If this exception is an instance of HttpException
            if ($this->isHttpException($exception)) {
                // Grab the HTTP status code from the Exception
                $status = $exception->getStatusCode();
            }


            // Return a JSON response with the response array and status code

            return response()->json($response, $status);
        }

    }


}
