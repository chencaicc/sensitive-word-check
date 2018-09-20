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
     * @param string $keyword
     * @throws Exception 异常表示失败
     */
    public function addWordToBlackList($keyword);

    /**
     * @param string $keyword
     * @throws Exception 异常表示失败
     */
    public function deleteWordFromBlackList($keyword);

    /**
     * @param string $keyword
     * @throws Exception 异常表示失败
     */
    public function addWordToWhiteList($keyword);

    /**
     * @param string $keyword
     * @throws Exception 异常表示失败
     */
    public function deleteWordFromWhiteList($keyword);

    /**
     * @return string
     */
    public function getIllegalKeyword();
}