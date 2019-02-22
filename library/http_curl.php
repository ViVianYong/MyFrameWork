<?php
/**
 * HttpCurl Curl模拟Http工具类
 * @author TangDaYong
 * @date 2019/02/19
 */

class HttpCurl {
    // 存储实例
    private static $_instance = null;
    // 存储curl句柄
    private static $_ch       = null;
    // 请求头信息
    private $_headers         = array();
    // http代理
    private $_proxy           = null;
    // 超时时间，单位为秒
    private $_timeout         = 5;

    /**
     * 单例模式下，禁止构造函数
     */
    private function __construct() {

    }
    /**
     * 初始化curl资源
     */
    private static function _initCurl() {
        if (self::$_ch === null) {
            self::$_ch = curl_init();
        }
    }
    /**
     * 单例模式获取实例
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 设置http 头信息
     * @param string $header 头信息
     */
    public function setHeader($header) {
        self::_initCurl();

        if (is_array($header)){
            curl_setopt(self::$_ch, CURLOPT_HTTPHEADER, $header);
        }
    }

    /**
     * 设置http超时
     * @param int $time 超时时间
     */
    public function setTimeout($time = 0) {
        self::_initCurl();

        // 不能小于等于0
        if ($time <= 0) {
            $time = $this->_timeout;
        }
        curl_setopt(self::$_ch, CURLOPT_TIMEOUT, $time);
    }


    /**
     * 设置http代理
     * @param string $proxy 代理信息
     */
    public function setProxy($proxy) {
        self::_initCurl();

        if ($proxy) {
            curl_setopt (self::$_ch, CURLOPT_PROXY, $proxy);
        }
    }

    /**
     * 设置http 代理端口
     * @param int $port
     */
    public function setProxyPort($port) {
        self::_initCurl();

        if (is_int($port)) {
            curl_setopt(self::$_ch, CURLOPT_PROXYPORT, $port);
        }
    }

    /**
     * 设置页面来源
     * @param string $referer
     */
    public function setReferer($referer = ""){
        self::_initCurl();

        if (!empty($referer)) {
            curl_setopt(self::$_ch, CURLOPT_REFERER , $referer);
        }
    }

    /**
     * 设置用户代理
     * @param string $agent
     */
    public function setUserAgent($agent = "") {
        self::_initCurl();

        if ($agent) {
            // 模拟用户使用的浏览器
            curl_setopt(self::$_ch, CURLOPT_USERAGENT, $agent);
        }
    }

    /**
     * http响应中是否显示header
     * @param int $show 1表示显示
     */
    public function showResponseHeader($show) {
        self::_initCurl();

        curl_setopt(self::$_ch, CURLOPT_HEADER, $show);
    }

    /**
     * 设置证书路径
     * @param $file
     */
    public function setCainfo($file) {
        self::_initCurl();

        curl_setopt(self::$_ch, CURLOPT_CAINFO, $file);
    }


    /**
     * 模拟GET请求
     * @param string $url
     * @param string $dataType
     * @return bool|mixed
     */
    public function get($url, $params = array(), $dataType = 'json') {
        self::_initCurl();

        // 设置头信息
        if (isset($params['header'])) {
            $this->setHeader($params['header']);

            unset($params['header']);
        }
        // 设置超时时间
        if (isset($params['timeout'])) {
            $time = intval($params['timeout']);
            $this->setTimeout($time);

            unset($params['timeout']);
        }
        // 设置代理
        if (isset($params['proxy'])) {
            $this->setProxy($params['proxy']);

            unset($params['proxy']);
        }
        // 设置代理端口
        if (isset($params['port'])) {
            $this->setProxyPort($params['port']);

            unset($params['port']);
        }
        // 设置页面来源
        if (isset($params['referer'])) {
            $this->setReferer($params['referer']);

            unset($params['referer']);
        }

        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt(self::$_ch, CURLOPT_SSLVERSION, 1);
        }
        // 设置get参数
        if (!empty($params) && is_array($params)) {
            if (strpos($url, '?') !== false) {
                $url .= http_build_query($params);
            } else {
                $url .= '?' . http_build_query($params);
            }
        }
        // end 设置get参数
        curl_setopt(self::$_ch, CURLOPT_URL, $url);
        curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, 1 );
        $content = curl_exec(self::$_ch);
        $status = curl_getinfo(self::$_ch);
        curl_close(self::$_ch);

        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($dataType == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }

        return FALSE;
    }

    /**
     * 模拟POST请求
     * @param string $url
     * @param array $fields
     * @param string $dataType
     * @return mixed
     */
    public function post($url, $params = array(), $dataType = 'json') {
        self::_initCurl();

        // 设置头信息
        if (isset($params['header'])) {
            $this->setHeader($params['header']);

            unset($params['header']);
        }
        // 设置超时时间
        if (isset($params['timeout'])) {
            $time = intval($params['timeout']);
            $this->setTimeout($time);

            unset($params['timeout']);
        }
        // 设置代理
        if (isset($params['proxy'])) {
            $this->setProxy($params['proxy']);

            unset($params['proxy']);
        }
        // 设置代理端口
        if (isset($params['port'])) {
            $this->setProxyPort($params['port']);

            unset($params['port']);
        }
        // 设置页面来源
        if (isset($params['referer'])) {
            $this->setReferer($params['referer']);

            unset($params['referer']);
        }

        if(stripos($url, 'https://') !== FALSE) {
            curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt(self::$_ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt(self::$_ch, CURLOPT_URL, $url);

        // 设置post body
        if (!empty($params)) {
            if (is_array($params)) {
                curl_setopt(self::$_ch, CURLOPT_POSTFIELDS, http_build_query($params));
            } elseif (is_string($params)) {
                curl_setopt(self::$_ch, CURLOPT_POSTFIELDS, $params);
            }
        }
        // end 设置post body
        curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt(self::$_ch, CURLOPT_POST, true);
        $content = curl_exec(self::$_ch);
        $status = curl_getinfo(self::$_ch);
        curl_close(self::$_ch);

        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($dataType == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }
        return FALSE;
    }
}
