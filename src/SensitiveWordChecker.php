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
    private $illegalWord = '';

    /**
     * @param Dictionary 非法词字典
     */
    public $blackDictionary;

    /**
     * @param Dictionary 合法词字典
     */
    public $whiteDictionary;

    /**
     * @param string $blackWordPath 非法词文件路径
     * @param string $whiteWordPath 合法词文件路径
     * @throws 文件权限异常、系统异常
     */
    public function __construct($blackWordPath, $whiteWordPath)
    {
        $this->blackDictionary = new Dictionary($blackWordPath);
        $this->whiteDictionary = new Dictionary($whiteWordPath);
    }

    /**
     * @param string $content 检测内容
     * @return bool   true 合法的   false 非法的
     */
    public function isValid($content)
    {
        $check = true;//合法
        foreach ($this->blackDictionary->getKeywords() as $bword) {
            // 命中黑名单
            $start = strpos($content, $bword);//命中黑名单在内容中的位置
            if ($start !== false) {
                $this->illegalWord = $bword;
                $check = false;//非法
                foreach ($this->whiteDictionary->getKeywords() as $wword) {
                    // $wword = $wword['word'];
                    // 存在白词中可能合法的
                    $pos = strpos($wword, $bword);//黑名单在白名单中命中的位置
                    if ($pos !== false) {
                        //计算截取的起始下标
                        $subStart = $start - $pos;//截取位置
                        // 非法
                        if ($subStart < 0) {
                            $check = false;
                            // 可能合法
                        } else {
                            $sub = substr($content, $subStart, strlen($wword));
                            if ($sub === $wword) {
                                $this->illegalWord = null;
                                $check = true;
                                break;
                            } else {
                                $check = false;
                            }
                        }
                    }
                }
                // 没通过白名单
                if ($check === false) {
                    break;
                }
            }
        }
        return $check;
    }

    /**
     * @return string   检测到的非法词
     */
    public function getIllegalWord()
    {
        return $this->illegalWord;
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function addWordToBlackList($keyword)
    {
        if (!$this->whiteDictionary->exist($keyword)) {
            $this->blackDictionary->add($keyword);
        }
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常，无异常表示操作成功
     */
    public function deleteWordFormBlackList($keyword)
    {
        $this->blackDictionary->delete($keyword);
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function addWordToWhiteList($keyword)
    {
        if (!$this->blackDictionary->exist($keyword)) {
            $this->whiteDictionary->add($keyword);
        }
    }

    /**
     * @param string $keyword
     * @throws 文件写入权限异常
     */
    public function deleteWordFromWhiteList($keyword)
    {
        $this->whiteDictionary->delete($keyword);
    }
}