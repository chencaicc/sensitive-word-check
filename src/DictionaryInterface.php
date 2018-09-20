<?php
namespace Chencaicc\SensitiveWordCheck;

interface DictionaryInterface
{

    /**
     * @param string /array $keyword
     * @param object $otherObj 另一部字典[关键词不要出现在另一部字典里面，否则不可以添加]
     * @return bool
     */
    public function addKeyword($keyword);

    /**
     * @param string /array $keyword
     * @return bool
     */
    public function deleteKeyword($keyword);

}
