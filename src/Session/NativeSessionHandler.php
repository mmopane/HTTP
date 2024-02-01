<?php

namespace MMOPANE\Http\Session;

use MMOPANE\Collection\CollectionInterface;

class NativeSessionHandler implements SessionHandlerInterface
{
    /**
     * @var bool
     */
    protected bool $isStarted = false;
    /**
     * @var NativeSessionCollection
     */
    protected NativeSessionCollection $session;

    /**
     * @return bool
     */
    public function start(): bool
    {
        $this->isStarted = session_start();
        $this->session = new NativeSessionCollection();
        return $this->isStarted;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return session_id();
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        session_id($id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return session_name();
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        session_name($name);
    }

    /**
     * @return void
     */
    public function save(): void
    {
        session_write_close();
        $this->isStarted = false;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->session->clear();
    }

    /**
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface
    {
        return $this->session;
    }
}