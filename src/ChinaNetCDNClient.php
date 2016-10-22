<?php
namespace eddy;

class ChinaNetCDNClient
{
    public $timeout = 10;

    protected $username;
    protected $password;

    protected $url = 'http://ccm.chinanetcenter.com/ccm/servlet/';

    /**
     * @var \GuzzleHttp\Client Http Client
     */
    protected $httpClient;

    function __construct($username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->httpClient = new \GuzzleHttp\Client();
    }

    public function __call($name, $arguments)
    {
        if (empty($arguments)) {
            $origin_params = [];
        } else {
            if (isset($arguments[0]) && is_array($arguments[0])) {
                $origin_params = $arguments[0];
            } else {
                throw new \InvalidArgumentException('the type of argument must be array.');
            }
        }
        $origin_params['username'] = $this->username;
        if ($name === 'contReceiver') {
            $urls = '';
            if (isset($origin_params['url'])) {
                $urls = $origin_params['url'];
            }
            if (isset($origin_params['dir'])) {
                $urls .= $origin_params['dir'];
            }
            if ($urls === '') {
                throw new \InvalidArgumentException('missing url or dir parameter.');
            }
            $origin_params['passwd'] = md5($this->username . $this->password . $urls);
        } else {
            $origin_params['password'] = $this->getSign();
        }

        $query = $this->getQuery($origin_params);

        try {
            $response = $this->httpClient->get($this->url . $name . '?' . $query, ['timeout' => $this->timeout]);
            return $response;
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $response = $e->getResponse();
            return $response;
        }
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    protected function getQuery($params)
    {
        return http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    protected function getSign()
    {
        return md5($this->username . $this->password);
    }
}