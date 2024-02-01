<?php

namespace MMOPANE\Http;

use DateTimeInterface;

class Cookie
{
    public const SAMESITE_NONE = 'none';
    public const SAMESITE_LAX = 'lax';
    public const SAMESITE_STRICT = 'strict';

    private const RESERVED_CHARS_FROM = ['=', ',', ';', ' ', "\t", "\r", "\n", "\v", "\f"];
    private const RESERVED_CHARS_TO = ['%3D', '%2C', '%3B', '%20', '%09', '%0D', '%0A', '%0B', '%0C'];

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|null
     */
    protected string|null $value = null;

    /**
     * @var int
     */
    protected int $expire = 0;

    /**
     * @var string
     */
    protected string $path = '/';

    /**
     * @var string|null
     */
    protected string|null $domain = null;

    /**
     * @var bool
     */
    protected bool $secure = false;

    /**
     * @var bool
     */
    protected bool $httpOnly = false;

    /**
     * @var string|null
     */
    protected string|null $sameSite = null;

    /**
     * @var bool
     */
    protected bool $partitioned = false;

    /**
     * @param string $name
     * @param string|null $value
     * @param int|string|DateTimeInterface $expire
     * @param string $path
     * @param string|null $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @param string|null $sameSite
     * @param bool $partitioned
     */
    public function __construct(
        string $name,
        string|null $value = null,
        int|string|DateTimeInterface $expire = 0,
        string $path = '/',
        string|null $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        string|null $sameSite = self::SAMESITE_LAX,
        bool $partitioned = false
    )
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setExpire($expire);
        $this->setPath($path);
        $this->setDomain($domain);
        $this->setSecure($secure);
        $this->setHttpOnly($httpOnly);
        $this->setSameSite($sameSite);
        $this->setPartitioned($partitioned);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        if(empty($name))
            throw new \InvalidArgumentException('The cookie name cannot be empty.');

        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setValue(string|null $value = null): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): string|null
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return !is_null($this->value);
    }

    /**
     * @param int|string|DateTimeInterface $expire
     * @return $this
     */
    public function setExpire(int|string|DateTimeInterface $expire = 0): static
    {
        $this->expire = $this->expireTimestamp($expire);
        return $this;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setDuration(int $seconds = 0): static
    {
        $this->setExpire(time() + $seconds);
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return max(0, $this->getExpire() - time());
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path = '/'): static
    {
        $this->path = empty($path) ? '/' : $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string|null $domain
     * @return $this
     */
    public function setDomain(string|null $domain = null): static
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDomain(): string|null
    {
        return $this->domain;
    }

    /**
     * @return bool
     */
    public function hasDomain(): bool
    {
        return !is_null($this->domain);
    }

    /**
     * @param bool $secure
     * @return $this
     */
    public function setSecure(bool $secure = false): static
    {
        $this->secure = $secure;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @param bool $httpOnly
     * @return $this
     */
    public function setHttpOnly(bool $httpOnly = false): static
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param string|null $sameSite
     * @return $this
     */
    public function setSameSite(string|null $sameSite = null): static
    {
        $sameSite = empty($sameSite) ? null : strtolower($sameSite);
        if(!in_array($sameSite, [self::SAMESITE_LAX, self::SAMESITE_STRICT, self::SAMESITE_NONE, null], true))
            throw new \InvalidArgumentException('The "sameSite" parameter value is not valid.');
        $this->sameSite = $sameSite;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSameSite(): string|null
    {
        return $this->sameSite;
    }

    /**
     * @return bool
     */
    public function hasSameSite(): bool
    {
        return !is_null($this->sameSite);
    }

    /**
     * @param bool $partitioned
     * @return $this
     */
    public function setPartitioned(bool $partitioned = false): static
    {
        $this->partitioned = $partitioned;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPartitioned(): bool
    {
        return $this->partitioned;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $segments = [];

        $name = str_replace(self::RESERVED_CHARS_FROM, self::RESERVED_CHARS_TO, $this->getName());
        $value = $this->hasValue() ? rawurlencode($this->getValue()) : 'deleted';

        $segments[] = $name . '=' . $value;

        if($this->hasValue())
        {
            if($this->getExpire() !== 0)
            {
                $segments[] = 'expires=' . gmdate('D, d M Y H:i:s T', $this->getExpire());
                $segments[] = 'Max-Age=' . $this->getDuration();
            }
        }
        else
        {
            $segments[] = 'expires=' . gmdate('D, d M Y H:i:s T', time() - 31536001);
            $segments[] = 'Max-Age=0';
        }

        if($this->getPath())
            $segments[] = 'path=' . $this->getPath();

        if($this->getDomain())
            $segments[] = 'domain=' . $this->getDomain();

        if($this->isSecure())
            $segments[] = 'secure';

        if($this->isHttpOnly())
            $segments[] = 'httponly';

        if($this->hasSameSite())
            $segments[] = 'samesite=' . $this->getSameSite();

        if($this->isPartitioned())
            $segments[] = 'partitioned';

        return implode('; ', $segments);
    }

    /**
     * @param int|string|DateTimeInterface $expire
     * @return int
     */
    protected function expireTimestamp(int|string|DateTimeInterface $expire): int
    {
        if(is_numeric($expire))
            return max($expire, 0);

        if($expire instanceof \DateTimeInterface)
            $expire = $expire->format('U');

        $expire = strtotime($expire);

        if($expire === false)
            throw new \InvalidArgumentException('The cookie expiration time is not valid.');

        return $expire;
    }
}