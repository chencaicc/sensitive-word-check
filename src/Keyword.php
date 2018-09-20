<?php
namespace Chencaicc\SensitiveWordCheck;

class Keyword implements KeywordInterface{

    /**
     * @param string 命中非法的单词
     */
    private $illegalWord=null;

    /**
     * @param Dictionary 非法词字典
     */
    public $blackDictionary;

    /**
     * @param Dictionary 合法词字典
     */
    public $whiteDictionary;

    /**
     * @param string $blackWordPath  非法词文件路径
     * @param string $whiteWordPath  合法词文件路径
     * @throws 文件权限异常、系统异常
     */
    public function __construct($blackWordPath,$whiteWordPath){
        $this->blackDictionary=new Dictionary($blackWordPath);
        $this->whiteDictionary=new Dictionary($whiteWordPath);
    }

    /**
     * @param string $content  检测内容
     * @return bool   ture 通过检测，所有词合法   false未通过检测，出现非法词
     */
    public function validate($content){
        $check=true;//合法
        foreach($this->blackDictionary as $bword){
            $bword=$bword['word'];
            // 命中黑名单
            $start=strpos($content, $bword);//命中黑名单在内容中的位置
            if($start!==false){
                $this->illegalWord=$bword;
                $check=false;//非法
                foreach($this->whiteDictionary as $wword){
                    $wword=$wword['word'];
                    // 存在白词中可能合法的
                    $pos=strpos($wword, $bword);//黑名单在白名单中命中的位置
                    if($pos!==false){
                        //计算截取的起始下标
                        $subStart = $start - $pos;//截取位置
                        // 非法
                        if($subStart<0){
                            $check=false;
                        // 可能合法
                        }else{
                            $sub = substr($content, $subStart,strlen($wword));
                            if($sub===$wword){
                                $this->illegalWord=null;
                                $check=true;
                                break;
                            }else{
                                $check=false;
                            }
                        }
                    }
                }
                // 没通过白名单
                if($check===false){
                    break;
                }
            }
        }
        return $check;
    }

    /**
     * @return string   检测到的非法词
     */
    public function getIllegalWord(){
        return (string)$this->illegalWord;
    }

    /**
     * @param string $keyword 
     * @return bool   ture 添加成功 false 添加失败
     */
    public function addBlackKeyword($keyword){
        return $this->blackDictionary->addWord($keyword,$this->whiteDictionary);
    }

    /**
     * @param string $keyword 
     * @return bool   ture 删除成功 false 删除失败
     */
    public function deleteBlackKeyword($keyword){
        return $this->blackDictionary->delWord($keyword,$this->whiteDictionary);
    }

    /**
     * @param string $keyword 
     * @return bool   ture 添加成功 false 添加失败
     */
    public function addWhiteKeyword($keyword){
        return $this->whiteDictionary->addWord($keyword,$this->blackDictionary);
    }

    /**
     * @param string $keyword 
     * @return bool   ture 删除成功 false 删除失败
     */
    public function deleteWhiteKeyword($keyword){
        return $this->whiteDictionary->delWord($keyword,$this->blackDictionary);
    }
}