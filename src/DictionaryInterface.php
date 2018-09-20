<?php

namespace Chencaicc\SensitiveWordCheck;

/**
 * Interface DictionaryInterface
 * @package Chencaicc\SensitiveWordCheck
 */
interface DictionaryInterface
{

    /**
     * @param array|string $keyword
     * @return mixed
     */
    public function add($keyword);

    /**
     * @param array|string $keyword
     * @return mixed
     */
    public function delete($keyword);

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword);

    /**
     * @return array
     */
    public function getKeywords();
}
