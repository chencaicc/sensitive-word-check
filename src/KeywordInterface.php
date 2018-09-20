<?php
namespace Chencaicc\SensitiveWordCheck;

interface KeywordInterface
{

    /**
     * @param string $content 检测内容
     * @return bool   true 通过检测，所有词合法   false未通过检测，出现非法词
     */
    public function validate($content);

    /**
     * @param string $keyword
     * @return bool   true 添加成功 false 添加失败
     */
    public function addBlackKeyword($keyword);

    /**
     * @param string $keyword
     * @return bool   true 删除成功 false 删除失败
     */
    public function deleteBlackKeyword($keyword);

    /**
     * @param string $keyword
     * @return bool   true 添加成功 false 添加失败
     */
    public function addWhiteKeyword($keyword);

    /**
     * @param string $keyword
     * @return bool   true 删除成功 false 删除失败
     */
    public function deleteWhiteKeyword($keyword);
}