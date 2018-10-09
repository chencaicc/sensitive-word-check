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
     * @throws 文件权限异常、系统异常
     */
    public function __construct($initDirectory)
    {
        if (!is_dir($initDirectory)) {
            $isDir = @mkdir($initDirectory, 0777, true);
            if (!$isDir)
                throw new \Exception('存放目录无法创建！');
        }
        $whiteListPath = $initDirectory . DIRECTORY_SEPARATOR . 'whitelist.txt';
        $blackListPath = $initDirectory . DIRECTORY_SEPARATOR . 'blacklist.txt';
        $this->whiteListDictionary = new Dictionary($whiteListPath);
        $this->blackListDictionary = new Dictionary($blackListPath);
    }

    /**
     * @param string $content 检测内容
     * @return bool   true 合法的   false 非法的
     */
    public function isValid($content)
    {
        // 去掉白名单中的词
        if (!empty($this->whiteListDictionary->getKeywords()))
            $content = str_replace($this->whiteListDictionary->getKeywords(), '', $content);
        // 遍历黑名单
        foreach ($this->blackListDictionary->getKeywords() as $word) {
            //出现敏感词
            if (strpos($content, $word) !== false) {
                $this->illegalKeyword = $word;
                return false;
            }
        }
        return true;
    }

    /**
     * @return string   检测到的非法词
     */
    public function getIllegalKeyword()
    {
        return $this->illegalKeyword;
    }

    /**
     * @param string|array $keyword
     * @throws 文件写入权限异常
     */
    public function addToBlackList($keyword)
    {
        if (is_string($keyword)) {
            if (!$this->whiteListDictionary->exist($keyword)) {
                //如果在字典中则抛异常
                if ($this->blackListDictionary->exist($keyword))
                    throw new InvalidArgumentException("关键词已经存在！");
                $this->blackListDictionary->add($keyword);
            }
        } elseif (is_array($keyword)) {
            foreach ($keyword as $word) {
                $this->addToBlackList($word);
            }
        } else {
            throw new InvalidArgumentException("非法入参！");
        }
    }

    /**
     * @param string|array $keyword
     * @throws 文件写入权限异常，无异常表示操作成功
     */
    public function deleteFromBlackList($keyword)
    {
        if (!is_string($keyword) && !is_array($keyword)) {
            throw new InvalidArgumentException("非法入参！");
        }
        $this->blackListDictionary->delete($keyword);
    }

    /**
     * @param string|array $keyword
     * @throws 文件写入权限异常
     */
    public function addToWhiteList($keyword)
    {
        if (is_string($keyword)) {
            if (!$this->blackListDictionary->exist($keyword)) {
                //如果在字典中则抛异常
                if ($this->whiteListDictionary->exist($keyword))
                    throw new InvalidArgumentException("关键词已经存在！");
                $this->whiteListDictionary->add($keyword);
            }
        } elseif (is_array($keyword)) {
            foreach ($keyword as $word) {
                $this->addToWhiteList($word);
            }
        } else {
            throw new InvalidArgumentException("非法入参！");
        }
    }

    /**
     * @param string|array $keyword
     * @throws 文件写入权限异常
     */
    public function deleteFromWhiteList($keyword)
    {
        if (!is_string($keyword) && !is_array($keyword)) {
            throw new InvalidArgumentException("非法入参！");
        }
        $this->whiteListDictionary->delete($keyword);
    }
}