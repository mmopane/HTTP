<?php

namespace MMOPANE\Http\Session;

use MMOPANE\Collection\CollectionInterface;

class NativeSessionCollection implements CollectionInterface
{
    /**
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(mixed $key, mixed $default = null): mixed
    {
        if(array_key_exists($key, $_SESSION))
            return $_SESSION[$key];

        return $default instanceof \Closure ? $default($key) : $default;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function add(mixed $value): static
    {
        $_SESSION[] = $value;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function put(mixed $key, mixed $value): static
    {
        $this->offsetSet($key, $value);
        return $this;
    }

    /**
     * @param ...$keys
     * @return bool
     */
    public function has(...$keys): bool
    {
        foreach ($keys as $key)
            if(!array_key_exists($key, $_SESSION))
                return false;
        return true;
    }

    /**
     * @param mixed $key
     * @return $this
     */
    public function forget(mixed $key): static
    {
        $keys = is_array($key) ? $key : func_get_args();
        foreach ($keys as $value)
            $this->offsetUnset($value);
        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * @return $this
     */
    public function clear(): static
    {
        $_SESSION = [];
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() <= 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($_SESSION);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($_SESSION[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $_SESSION[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if(is_null($offset))
            $_SESSION[] = $value;
        else
            $_SESSION[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($_SESSION[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($_SESSION);
    }
}