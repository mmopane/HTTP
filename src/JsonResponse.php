<?php

namespace MMOPANE\Http;

class JsonResponse extends Response
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     */
    public function __construct(array $data = [], int $status = 200, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->setData($data);
        $this->headers->put('Content-Type', "application/json; charset=$this->charset");
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return void
     */
    public function sendContent(): void
    {
        echo json_encode($this->data);
    }
}