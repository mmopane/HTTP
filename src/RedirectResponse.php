<?php

namespace MMOPANE\Http;

class RedirectResponse extends Response
{
    /**
     * @param string $url
     * @param int $status
     * @param array $headers
     */
    public function __construct(string $url = '/', int $status = 302, array $headers = [])
    {
        parent::__construct('', $status, $headers);

        if(!$this->isRedirection())
            throw new \InvalidArgumentException('The HTTP status code [' . $status . '] is not a redirect.');

        $this->setURL($url);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setURL(string $url): static
    {
        $this->headers->put('Location', $url);
        return $this;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->headers->get('Location');
    }
}