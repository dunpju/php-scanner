<?php


namespace Dengpju\PhpScanner;


use Exception;

class Authenticator
{
    /**
     * @var Configure
     */
    protected $config;

    /**
     * @var IStorage
     */
    protected $storage;

    /**
     * @var IAuthentication
     */
    protected $authentication;

    /**
     * @var string
     */
    protected $uniMd5;


    /**
     * Auth constructor.
     * @param Configure $config
     * @param IStorage $storage
     * @throws Exception
     */
    public function __construct(Configure $config, IStorage $storage)
    {
        if (is_dir($config->projectName)) {
            throw new Exception("projectName property Can't be empty");
        }
        $this->config = $config;
        $this->storage = $storage;
    }

    /**
     * @param IAuthentication $authentication
     */
    public function setAuthentication(IAuthentication $authentication): void
    {
        $this->authentication = $authentication;
    }

    /**
     * 是否需要鉴权
     * @param string $class
     * @param string $name
     * @return bool
     */
    public function isAuth(string $class, string $name): bool
    {
        $this->uniMd5 = Util::md5($this->config->projectName, $class, $name);
        return $this->storage->exist($this->uniMd5);
    }

    /**
     * 鉴定
     * @param array $myPrivilege
     * @param array $requestParams
     * @return bool
     */
    public function identify(array $myPrivilege, array $requestParams): bool
    {
        if ($this->authentication instanceof IAuthentication) {
            return $this->authentication->identify($myPrivilege, $requestParams);
        } else {
            if (isset($myPrivilege[$this->uniMd5]) && $currentPrivilege = $myPrivilege[$this->uniMd5]) {
                $parameters = isset($currentPrivilege['parameter']) ? $currentPrivilege['parameter'] : [];
                if ($parameters) {
                    $tmpParameters = [];
                    foreach ($parameters as $key => $parameter) {
                        $tmpParameters = array_merge($tmpParameters, json_decode($parameter, true));
                    }
                    $parameters = $tmpParameters;
                    $pass = false;
                    foreach ($parameters as $parameterName => $parameterValue) {
                        //请求参数中存在，则对比值
                        if (isset($requestParams[$parameterName]) && $requestParams[$parameterName] == $parameterValue) {
                            $pass = true;
                            break;
                        }
                    }
                    return $pass;
                }
                return true;
            } else {
                return false;
            }
        }
    }
}