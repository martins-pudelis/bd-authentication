<?php

namespace BdAuthentication;

interface AuthenticationHistoryInterface
{
    public function setBrowserSignature($browserSignature);

    /**
     * @return string
     */
    public function getBrowserSignature();

    /**
     * @param string $insertDate
     */
    public function setInsertDate($insertDate);

    /**
     * @return string
     */
    public function getInsertDate();

    /**
     * @param string $ip
     */
    public function setIp($ip);

    /**
     * @return string
     */
    public function getIp();

    /**
     * @param string $result
     */
    public function setResult($result);

    /**
     * @return string
     */
    public function getResult();

    /**
     * @param \BdUser\Entity\User $user
     */
    public function setUser($user);

    /**
     * @return \BdUser\Entity\User
     */
    public function getUser();
}