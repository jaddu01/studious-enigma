<?php


namespace App\Helpers;

use stdClass;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ResponseBuilder
{

    private $status;

    private $msg = null;

    private $data = null;

    private $httpCode = 200;

    private $meta = null;

    private $link = null;

    private $authToken = null;

    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    public static function success($data, $msg = null, $httpCode = 200): HttpResponse  {
        return self::asSuccess()
            ->withData($data)
            ->withMessage($msg)
            ->withHttpCode($httpCode)
            ->build();
    }

    public static function successWithPagination($query, $data, $msg = null, $httpCode = 200): HttpResponse  {
        return self::asSuccess()
            ->withData($data)
            ->withMessage($msg)
            ->withHttpCode($httpCode)
            ->withPagination($query)
            ->build();
    }

    public static function successWithToken($token, $data, $msg = null, $httpCode = 200): HttpResponse  {
        return self::asSuccess()
            ->withAuthToken($token)
            ->withData($data)
            ->withMessage($msg)
            ->withHttpCode($httpCode)
            ->build();
    }

    public static function error($msg, $httpCode, $data = null) {
        return self::asError()
            ->withData($data)
            ->withMessage($msg)
            ->withHttpCode($httpCode)
            ->build();
    }

    public static function asSuccess(): self
    {
        return new self(1);
    }

    public static function asError(): self
    {
        return new self(0);
    }

    public function withMessage(string $msg = null): self {
        $this->msg = $msg;

        return $this;
    }

    public function withData($data = null): self {
        $this->data = $data;

        return $this;
    }

    public function withHttpCode(int $httpCode = 200): self {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withPagination($query) {
        $this->meta = [
            'total_page' => $query->lastPage(),
            'current_page' => $query->currentPage(),
            'total_item' => $query->total(),
            'per_page' => (int)$query->perPage(),
        ];

        $this->link = [
            'next' => $query->hasMorePages(),
            'prev' => boolval($query->previousPageUrl())
        ];

        return $this;
    }

    public function withAuthToken(string $token = null) {
        $this->authToken = $token;

        return $this;
    }

    public function build(): HttpResponse {
        $response['status'] = $this->status;

        !is_null($this->msg) && $response['message'] = $this->msg;
        !is_null($this->authToken) && $response['auth_token'] = $this->authToken;
        !is_null($this->data) && $response['data'] = $this->data;
        !is_null($this->meta) && $response['meta'] = $this->meta;
        !is_null($this->link) && $response['link'] = $this->link;

        return response($response, $this->httpCode);
    }
    public static function json($msg = "", $data = [], $http_status_code = 200, $errors = NULL, $headers = [])
    {
        if (empty($data)) {
            $data = new stdClass();
        }

        if (empty($errors)) {
            $errors = new stdClass();
        }

        $body = [
            "message" => $msg,
            "errors" => $errors,
            "data"  => $data,
        ];

        return response()->json($body, $http_status_code, $headers, JSON_UNESCAPED_UNICODE);
    }
}
