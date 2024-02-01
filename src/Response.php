<?php

namespace MMOPANE\Http;

use MMOPANE\Collection\Collection;
use MMOPANE\Http\Collection\HeadersCollection;

class Response
{
    public const HTTP_CONTINUE = 100;
    public const HTTP_SWITCHING_PROTOCOLS = 101;
    public const HTTP_PROCESSING = 102;
    public const HTTP_EARLY_HINTS = 103;
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_RESET_CONTENT = 205;
    public const HTTP_PARTIAL_CONTENT = 206;
    public const HTTP_MULTI_STATUS = 207;
    public const HTTP_ALREADY_REPORTED = 208;
    public const HTTP_IM_USED = 226;
    public const HTTP_MULTIPLE_CHOICES = 300;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;
    public const HTTP_TEMPORARY_REDIRECT = 307;
    public const HTTP_PERMANENTLY_REDIRECT = 308;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const HTTP_REQUEST_TIMEOUT = 408;
    public const HTTP_CONFLICT = 409;
    public const HTTP_GONE = 410;
    public const HTTP_LENGTH_REQUIRED = 411;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public const HTTP_REQUEST_URI_TOO_LONG = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const HTTP_EXPECTATION_FAILED = 417;
    public const HTTP_I_AM_A_TEAPOT = 418;
    public const HTTP_MISDIRECTED_REQUEST = 421;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_LOCKED = 423;
    public const HTTP_FAILED_DEPENDENCY = 424;
    public const HTTP_TOO_EARLY = 425;
    public const HTTP_UPGRADE_REQUIRED = 426;
    public const HTTP_PRECONDITION_REQUIRED = 428;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
    public const HTTP_INSUFFICIENT_STORAGE = 507;
    public const HTTP_LOOP_DETECTED = 508;
    public const HTTP_NOT_EXTENDED = 510;
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @var array|string[]
     */
    public static array $statusTexts = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Content Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Content',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];


    /**
     * @var HeadersCollection
     */
    public Collection $headers;

    /**
     * @var string
     */
    protected string $content;

    /**
     * @var string
     */
    protected string $version;

    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var string
     */
    protected string $statusText;

    /**
     * @var string
     */
    protected string $charset;

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->headers = new HeadersCollection($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.1');
        $this->setCharset('UTF-8');

        $this->headers->put('Content-Type', "text/html; charset=$this->charset");
        $this->headers->put('Cache-Control', 'no-cache, must-revalidate');
        $this->headers->put('Expires', gmdate('D, d M Y H:i:s') . ' GMT');
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content = ''): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param int $code
     * @param string|null $text
     * @return $this
     */
    public function setStatusCode(int $code, string $text = null): static
    {
        if($code < 100 || $code >= 600)
            throw new \InvalidArgumentException("The HTTP status code [$code] is not valid.");

        $this->statusCode = $code;

        if(is_null($text))
            $this->statusText = self::$statusTexts[$code] ?? 'unknown status';
        else
            $this->statusText = $text;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setProtocolVersion(string $version): static
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $charset
     * @return $this
     */
    public function setCharset(string $charset): static
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @return $this
     */
    public function send(): static
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    /**
     * @return void
     */
    protected function sendHeaders(): void
    {
        header("HTTP/$this->version $this->statusCode $this->statusText", false, $this->getStatusCode());

        foreach($this->headers as $name => $value)
            header("$name: $value", false, $this->getStatusCode());

        foreach ($this->headers->getCookies() as $cookie)
            header("Set-Cookie: " . $cookie, false, $this->getStatusCode());
    }

    /**
     * @return void
     */
    protected function sendContent(): void
    {
        echo $this->content;
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }

    /**
     * @return bool
     */
    public function isInformational(): bool
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * @return bool
     */
    public function isRedirection(): bool
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return 200 === $this->statusCode;
    }

    /**
     * @return bool
     */
    public function isForbidden(): bool
    {
        return 403 === $this->statusCode;
    }

    /**
     * @return bool
     */
    public function isNotFound(): bool
    {
        return 404 === $this->statusCode;
    }

    /**
     * @param string|null $url
     * @return bool
     */
    public function isRedirect(string $url = null): bool
    {
        return in_array($this->statusCode, [201, 301, 302, 303, 307, 308]) && (is_null($url) || $url == $this->headers->get('Location'));
    }
}