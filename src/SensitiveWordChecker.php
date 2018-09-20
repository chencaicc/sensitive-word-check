<?php
namespace Chencaicc\SensitiveWordCheck;

/**
 * Class SensitiveWordChecker
 * @package Chencaicc\SensitiveWordCheck
 */
class SensitiveWordChecker implements SensitiveWordCheckerInterface
{

    /**
     * @param string 命中非法的单词
     */
    private $illegalKeyword = '';

    /**
     * @param Dictionary 非法词字典
     */
    public $blackListDictionary;

    /**
     * @param Dictionary 合法词字典
     */
    public $whiteListDictionary;

    /**
     * @param string $blackWordPath 非法词文件路径
     * @param string $whiteWordPath 合法词文件路径
     * @throws 文件权限异常、系统异常
     */
    public function __construct($blackWordPath, $whiteWordPath)
    {
        $this->blackListDictionary = new Dictionary($blackWordPath);
        $this->whiteListDictionary = new Dictionary($whiteWordPath);
    }

    /**
     * @param string $content 检测内容
     * @return bool   true 合法的   false 非法的
     */
    public function isValid($content)
    {
        $check = true;//合法
        // 去掉白名单中的词
        if(!empty($this->whiteListDictionary)){
            $content = str_replace($this->whiteListDictionary->getKeywords(), '', $content);
        }

        foreach ($this->blackListDictionary->getKeywords() as $word) {
            //出现敏感词
            if (strpos($content, $word) !== false) {
                $this->illegalKeyword = $word;
                return false;
            }
        }
        return $check;
    }

    /**
     * @return string   检测到的非法词
     */
    public function getIllegalKeyword()
    {
        return $this->illegalKeyword;
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function addWordToBlackList($keyword)
    {
        if (!$this->whiteListDictionary->exist($keyword)) {
            $this->blackListDictionary->add($keyword);
        }
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常，无异常表示操作成功
     */
    public function deleteWordFromBlackList($keyword)
    {
        $this->blackListDictionary->delete($keyword);
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function addWordToWhiteList($keyword)
    {
        if (!$this->blackListDictionary->exist($keyword)) {
            $this->whiteListDictionary->add($keyword);
        }
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function deleteWordFromWhiteList($keyword)
    {
        $this->whiteListDictionary->delete($keyword);
    }
}