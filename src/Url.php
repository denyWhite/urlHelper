<?php
declare(strict_types=1);

namespace App;

use Stringable;

/**
 * @link https://datatracker.ietf.org/doc/html/rfc3986
 */
class Url implements Stringable
{
    public const LOCALHOST = 'localhost';

    /** @var string http scheme */
    public const HTTP = 'http';

    /** @var string https scheme */
    public const HTTPS = 'https';

    private const URL_DELIMITER = '/';

    private ?string $scheme = null;

    private ?string $host = null;

    private ?int $port = null;

    private ?string $user = null;

    private ?string $pass = null;

    /**
     * @var list<string>
     */
    private array $pathParts = [];

    /**
     * @var array<string,string>
     */
    private array $queryParams = [];

    private ?string $fragment = null;

    /**
     * @throws WrongUrlException
     */
    public function __construct(?string $url = null)
    {
        if (is_null($url)) {
            return;
        }

        if (!self::isValidUrl($url)) {
            throw new WrongUrlException('Not valid url');
        }
        $urlParts = parse_url($url);

        if (array_key_exists('host', $urlParts)) {
            $this->host = $urlParts['host'];
        }

        if (array_key_exists('scheme', $urlParts)) {
            $this->scheme = $urlParts['scheme'];
        }
        if (array_key_exists('port', $urlParts)) {
            $this->port = $urlParts['port'];
        }
        if (array_key_exists('user', $urlParts)) {
            $this->user = $urlParts['user'];
        }
        if (array_key_exists('pass', $urlParts)) {
            $this->pass = $urlParts['pass'];
        }
        if (array_key_exists('path', $urlParts)) {
            $this->pathParts = array_filter(explode(self::URL_DELIMITER, $urlParts['path']));
        }
        if (array_key_exists('query', $urlParts)) {
            parse_str($urlParts['query'], $params);
            $this->queryParams = $params;
        }
        if (array_key_exists('fragment', $urlParts)) {
            $this->fragment = $urlParts['fragment'];
        }
    }

    /**
     * @return string
     * @throws WrongUrlException
     */
    public function toString(): string
    {
        $result = '';
        if (!is_null($this->scheme)) {
            $result .= $this->scheme . ':' . self::URL_DELIMITER . self::URL_DELIMITER;
        }
        if (!is_null($this->user)) {
            $result .= $this->user;
        }
        if (!is_null($this->pass)) {
            if (is_null($this->user)) {
                throw new WrongUrlException('Pass without user');
            }
            $result .= ':' . $this->pass;
        }
        if (!is_null($this->user) || !is_null($this->pass)) {
            $result .= '@';
        }
        if (!is_null($this->host)) {
            $result .= $this->host;
        }
        if (!is_null($this->port)) {
            $result .= ':' . $this->port;
        }
        if (count($this->pathParts) > 0) {
            $result .= self::URL_DELIMITER . $this->getBuildPath();
        }
        if (count($this->queryParams) > 0) {
            $result .= '?' . $this->getBuildQuery();
        }
        if (!is_null($this->fragment)) {
            $result .= '#' . $this->fragment;
        }
        return $result;
    }

    /**
     * @return string
     * @throws WrongUrlException
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function isValidUrl(string $url): bool
    {
        return $url === filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @return $this
     */
    public function withHttpScheme(): self
    {
        $this->scheme = self::HTTP;
        return $this;
    }

    /**
     * @return $this
     */
    public function withHttpsScheme(): self
    {
        $this->scheme = self::HTTPS;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHttp(): bool
    {
        return $this->scheme === self::HTTP;
    }

    /**
     * @return bool
     */
    public function isHttps(): bool
    {
        return $this->scheme === self::HTTPS;
    }

    /**
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeScheme(): self
    {
        $this->scheme = null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeHost(): self
    {
        $this->host = null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return $this
     */
    public function removePort(): self
    {
        $this->port = null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return $this
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeUser(): self
    {
        $this->user = null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPass(): ?string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     * @return $this
     */
    public function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @return $this
     */
    public function removePass(): self
    {
        $this->pass = null;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPathParts(): array
    {
        return $this->pathParts;
    }

    /**
     * @param array $pathParts
     * @return $this
     */
    public function setPathParts(array $pathParts): self
    {
        $this->pathParts = $pathParts;
        return $this;
    }

    /**
     * @return $this
     */
    public function removePathParts(): self
    {
        $this->pathParts = [];
        return $this;
    }

    /**
     * @return string[]
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @param string $queryParam
     * @param string|array $queryValue
     * @return $this
     */
    public function addQueryParam(string $queryParam, string|array $queryValue): self
    {
        $this->queryParams[$queryParam] = $queryValue;
        return $this;
    }

    /**
     * @param array<string,string|array> $queryParams
     * @return $this
     */
    public function setQueryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @param string $queryParam
     * @return $this
     */
    public function removeQueryParam(string $queryParam): self
    {
        unset($this->queryParams[$queryParam]);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeQueryParams(): self
    {
        $this->queryParams = [];
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     * @return $this
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFragment(): self
    {
        $this->fragment = null;
        return $this;
    }

    /**
     * @return string|null
     */
    private function getBuildPath(): ?string
    {
        return implode(self::URL_DELIMITER, $this->pathParts);
    }

    /**
     * @return string|null
     */
    private function getBuildQuery(): ?string
    {
        return http_build_query($this->queryParams);
    }
}