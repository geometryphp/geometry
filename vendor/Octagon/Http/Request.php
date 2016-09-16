<?php

namespace Octagon\Http;

use Octagon\Adt\Bag;

/**
 * Request represents an HTTP request.
 */

class Request
{

    /**
     * Protocol.
     *
     * @var string
     */

    private $protocol;

    /**
     * The request method.
     *
     * @var string
     */
    private $method;

    /**
     * Full request path.
     *
     * @var string
     */
    private $fullPath;

    /**
     *  The request path.
     *
     * @var string
     */
    private $path;

    /**
     * The query string.
     *
     * @var string
     */
    private $queryString;

    /**
     * The raw HTTP-request body.
     *
     * @var string
     */
    private $body;

    /**
     * Stores key-value pairs from $_GET.
     *
     * @var \Octagon\Adt\Bag
     */
    private $query;

    /**
     * Stores key-value pairs from $_POST.
     * @var \Octagon\Adt\Bag
     */
    private $request;

    /**
     * Stores key-value pairs from SERVER.
     *
     * @var \Octagon\Adt\Bag
     */
    private $server;

    /**
     * Stores key-value pairs from FILES.
     *
     * @var \Octagon\Adt\Bag
     */
    private $files;

    /**
     * Stores key-value pairs from COOKIE.
     *
     * @var \Octagon\Adt\Bag
     */
    private $cookie;

    /**
     * Stores key-value pairs from (HEADERS).
     *
     * @var \Octagon\Adt\Bag
     */
    private $headers;

    /**
     * Stores request parameters as key-value pairs in a bag.
     *
     * @var \Octagon\Adt\Bag
     */
    private $params;

    /**
     * Create new Request instance.
     *
     * @var array $query   Expects $_GET
     * @var array $request Expects $_POST
     * @var array $server  Expects $_SERVER
     * @var array $files   Expects $_FILES
     * @var array $cookie  Expects $_COOKIE
     * @var array $headers Expects $_HEADERS?
     *
     * @return void
     */
    public function __construct($query = array(), $request = array(), $server = array(), $files = array(), $cookie = array(), $headers = array())
    {
        $this->initialize($query, $request, $server, $files, $cookie, $headers);
    }

    /**
     * Initialize Request.
     *
     * @var array $query
     * @var array $request
     * @var array $server
     * @var array $files
     * @var array $cookie
     * @var array $headers
     *
     * @return void
     */
    public function initialize($query = array(), $request = array(), $server = array(), $files = array(), $cookie = array(), $headers = array())
    {
        if ($query !== null) {
            $this->setQuery(new Bag($query));
        }
        if ($request !== null) {
            $this->setRequest(new Bag($request));
        }
        if ($server !== null) {
            $this->setServer(new Bag($server));
        }
        if ($files !== null) {
            $this->setFiles(new Bag($files));
        }
        if ($cookie !== null) {
            $this->setCookie(new Bag($cookie));
        }
        if ($headers !== null) {
            $this->setHeaders(new Bag($headers));
        }

        $this->setProtocol(null);
        $this->setMethod(null);
        $this->setFullPath(null);
        $this->setPath(null);
        $this->setBody(null);
        $this->setQueryString(null);
        $this->setParams(array());
    }

    /**
     * Return a new request and add capture environment variables.
     *
     * @return \Octagon\Routing\Request
     */
    public static function capture()
    {
        $request = new Request($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE, array()); // TODO: replace last array() with HEADER
        $request->setProtocol($request->server->get('SERVER_PROTOCOL'));
        $request->setMethod($request->server->get('REQUEST_METHOD'));
        $request->setFullPath($request->server->get('REQUEST_URI'));
        $request->setPath($request->server->get('PATH_INFO'));
        $request->setPath(self::normalizePath($request->getPath()));
        $request->setQueryString($request->server->get('QUERY_STRING'));
        $request->setBody(null); // TODO: get request body
        return $request;
    }

    /**
     * Set the request protocol.
     *
     * @var string $protocol Protocol to set.
     *
     * @return void
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Get the request protocol.
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Get the protocol version.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        // Extract version from the protocol
        $i = strpos($this->protocol,'/');
        $version = substr($this->protocol, $i);
        return $version;
    }

    /**
     * Get scheme.
     *
     * @return string
     */
    public function getScheme()
    {
        if ($this->isHttps()) {
            return 'https';
        }
        else {
            return 'http';
        }
    }

    /**
     * Check if scheme is HTTPS.
     *
     * @return bool Returns `true` if HTTPS. Otherwise, returns `false`.
     */
    public function isHttps()
    {
        if (!empty($this->server->get('HTTPS'))) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Set the request method.
     *
     * @var string $method Method to set.
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the full request path.
     *
     * @var string $fullPath Path to set.
     *
     * @return void
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * Get the full request path.
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Set the request path.
     *
     * @var string $path Path to set.
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the request path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
    * Utility: Normalize the given path.
    *
    * @var string $path Path to normalize.
    *
    * @return string Normalized path.
    */
    public static function normalizePath($path)
    {
        // Trim slashes and whitespaces from around the URI path because they are useless.
        $path = trim($path);
        $path = trim($path, '/');

        // Prepend a single leading slash
        $path = '/' . $path;

        // Return normalized path
        return $path;
    }

    /**
     * Set the query string.
     *
     * @var string $queryString Query string to set.
     *
     * @return void
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }

    /**
     * Get the query string.
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Set the request body.
     *
     * @var string $body Body text to set.
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get the request body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the query bag.
     *
     * @var array $query
     *
     * @return void
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get the query bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the request bag.
     *
     * @var array $request
     *
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Get the request bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the server bag.
     *
     * @var array $server
     *
     * @return void
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * Get the server bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set the files bag.
     *
     * @var array $files
     *
     * @return void
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * Get the files bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the cookie bag.
     *
     * @var array $cookie
     *
     * @return void
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Get the cookie bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set the headers bag.
     *
     * @var array $headers
     *
     * @return void
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get the headers bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the parameters bag.
     *
     * @var array $params
     *
     * @return void
     */
    public function setParams($params = array())
    {
        $this->params = new Bag($params);
    }

    /**
     * Get the parameters bag.
     *
     * @return \Octagon\Adt\Bag
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Get a parameter by key from the paramater bag.
     *
     * @var mixed $key Key to find parameter.
     *
     * @return mixed Value found by key.
     */
    public function param($key) {
        $params = $this->getParams();
        $param = $params->get($key);
        return $param;
    }

}
