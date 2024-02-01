<?php

namespace MMOPANE\Http;

use MMOPANE\Collection\Collection;

class Request
{
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    /**
     * @var Collection
     */
    public Collection $attributes;

    /**
     * @var Collection
     */
    public Collection $post;

    /**
     * @var Collection
     */
    public Collection $get;

    /**
     * @var Collection
     */
    public Collection $server;

    /**
     * @var Collection
     */
    public Collection $files;

    /**
     * @var Collection
     */
    public Collection $cookies;

    /**
     * @var string|null
     */
    protected string|null $path = null;

    /**
     * @var string|null
     */
    protected string|null $url = null;

    /**
     * @var string|null
     */
    protected string|null $baseUrl = null;

    /**
     * @param array $get
     * @param array $post
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     */
    public function __construct(array $get = [], array $post = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [])
    {
        $this->initialize($get, $post, $attributes, $cookies, $files, $server);
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @return void
     */
    protected function initialize(array $get = [], array $post = [], array $attributes = [], array $cookies = [], array $files = [], array $server = []): void
    {
        $this->get = new Collection($get);
        $this->post = new Collection($post);
        $this->attributes = new Collection($attributes);
        $this->cookies = new Collection($cookies);
        $this->files = new Collection($files);
        $this->server = new Collection($server);
    }

    /**
     * @return static
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ??= parse_url($this->server->get('REQUEST_URI', '/'), PHP_URL_PATH);
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->url ??= $this->getBaseURL() . $this->getPath();
    }

    /**
     * @return string
     */
    public function getBaseURL(): string
    {
        return $this->baseUrl ??= $this->getProtocol() . '://' . $this->server->get('HTTP_HOST', 'localhost');
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->server->get('REQUEST_SCHEME', 'http');
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server->get('REQUEST_METHOD', 'GET');
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->server->get('REQUEST_PORT', 80);
    }

    /**
     * @return string
     */
    public function getIP(): string
    {
        return $this->server->get('REMOTE_ADDR', '127.0.0.1');
    }
}