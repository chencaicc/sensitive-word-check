<?php
namespace Chencaicc\SensitiveWordCheck;

/**
 * Class SensitiveWordChecker
 * @package Chencaicc\SensitiveWordCheck
 */
interface SensitiveWordCheckerInterface
{

    /**
     * @param string $content 检测内容
     * @return bool   true 合法的   false 非法的
     */
    public function isValid($content);

    /**
     * @param string|array $keyword
     * @throws Exception 异常表示失败
     */
    public function addToBlackList($keyword);

    /**
     * @param string|array $keyword
     * @throws Exception 异常表示失败
     */
    public function deleteFromBlackList($keyword);

    /**
     * @param string|array $keyword
     * @throws Exception 异常表示失败
     */
    public function addToWhiteList($keyword);

    /**
     * @param string|array $keyword
     * @throws Exception 异常表示失败
     */
    public function deleteFromWhiteList($keyword);

    /**
     * @return string
     */
    public function getIllegalKeyword();
}