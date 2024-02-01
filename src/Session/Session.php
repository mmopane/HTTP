<?php

namespace MMOPANE\Http\Session;

use MMOPANE\Collection\CollectionInterface;
use Traversable;

class Session implements \IteratorAggregate, \Countable
{
    /**
     * @var SessionHandlerInterface|null
     */
    protected SessionHandlerInterface|null $storage = null;

    /**
     * @param SessionHandlerInterface|null $storage
     */
    public function __construct(SessionHandlerInterface|null $storage = null)
    {
        $this->storage = $storage ?? new NativeSessionHandler();
    }

    /**
     * Saving session data if session active.
     */
    public function __destruct()
    {
        $this->save();
    }

    /**
     * Start session.
     * @return bool
     */
    public function start(): bool
    {
        return $this->storage->start();
    }

    /**
     * Determine if session started.
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->storage->isStarted();
    }

    /**
     * Set session ID.
     * @param string $id
     * @return $this
     */
    public function setId(string $id): static
    {
        if($this->storage->isStarted())
            throw new \LogicException('Cannot change the ID of an active session.');
        $this->storage->setId($id);
        return $this;
    }

    /**
     * Get session ID.
     * @return string
     */
    public function getId(): string
    {
        return $this->storage->getId();
    }

    /**
     * Set session name.
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        if($this->storage->isStarted())
            throw new \LogicException('Cannot change the name of an active session.');
        $this->storage->setName($name);
        return $this;
    }

    /**
     * Get session name.
     * @return string
     */
    public function getName(): string
    {
        return $this->storage->getName();
    }

    /**
     * Saving session data if session active.
     * @return $this
     */
    public function save(): static
    {
        if($this->storage->isStarted())
            $this->storage->save();
        return $this;
    }

    /**
     * Clear session data.
     * @return $this
     */
    public function clear(): static
    {
        if($this->storage->isStarted())
            $this->storage->clear();
        return $this;
    }

    /**
     * Get an item from the session by name.
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->getAttributes()->get($name, $default);
    }

    /**
     * Put an item in the session by name.
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function put(string $name, mixed $value): static
    {
        $this->getAttributes()->put($name, $value);
        return $this;
    }

    /**
     * Determine if an item exists in the session by name.
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->getAttributes()->has($name);
    }

    /**
     * Remove an item from the session by name.
     * @param string $name
     * @return $this
     */
    public function forget(string $name): static
    {
        $this->getAttributes()->forget($name);
        return $this;
    }

    /**
     * Get all the items in the session.
     * @return array
     */
    public function all(): array
    {
        return $this->getAttributes()->all();
    }

    /**
     * Count the number of items in the session.
     * @return int
     */
    public function count(): int
    {
        return $this->getAttributes()->count();
    }

    /**
     * Determine if the collection is empty or not.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getAttributes()->isEmpty();
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->getAttributes()->all());
    }

    /**
     * @return CollectionInterface
     */
    protected function getAttributes(): CollectionInterface
    {
        if(!$this->storage->isStarted())
            throw new \RuntimeException('Unable to retrieve data from an inactive session.');

        return $this->storage->getCollection();
    }
}