<?php

namespace MMOPANE\Http\Session;

use MMOPANE\Collection\Collection;
use MMOPANE\Collection\CollectionInterface;

interface SessionHandlerInterface
{
    /**
     * @return bool
     */
    public function start(): bool;

    /**
     * @return bool
     */
    public function isStarted(): bool;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @return void
     */
    public function save(): void;

    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @return Collection
     */
    public function getCollection(): CollectionInterface;
}