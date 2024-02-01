<?php

namespace MMOPANE\Http\Collection;

use MMOPANE\Collection\Collection;
use MMOPANE\Http\Cookie;

class HeadersCollection extends Collection
{
    /**
     * @var Cookie[]
     */
    protected array $cookies = [];

    /**
     * @param Cookie $cookie
     * @return $this
     */
    public function setCookie(Cookie $cookie): static
    {
        $this->cookies[] = $cookie;
        return $this;
    }

    /**
     * @return Cookie[]
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }
}