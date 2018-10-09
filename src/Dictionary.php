<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 2018/9/20
 * Time: 13:05
 */

namespace Chencaicc\SensitiveWordCheck;

/**
 * Class Dictionary
 * @package Chencaicc\SensitiveWordCheck
 */
class Dictionary implements DictionaryInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    private $_keywords = [];

    /**
     * @var array
     */
    private $keywords = [];

    /**
     * @var bool
     */
    private $isChanged = false;

    /**
     * Dictionary constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        if (!file_exists($file)) {
            touch($file);
        }
        $this->file = $file;
        $fp = fopen($this->file, 'r');
        while (!feof($fp)) {
            $keyword = trim(fgets($fp));
            if ($keyword == '')
                continue;
            $this->_keywords[] = ['w' => $keyword, 'l' => $this->countKeyword($keyword)];
            $this->keywords[] = $keyword;
        }
        fclose($fp);
    }

    /**
     * @param array|string $keyword
     * @return void
     */
    public function add($keyword)
    {
        if (is_string($keyword)) {
            $keyword = [$keyword];
        }
        if (is_array($keyword)) {
            foreach ($keyword as $value) {
                if (!$this->exist($value)) {
                    array_push($this->_keywords, ['w' => $value, 'l' => $this->countKeyword($value)]);
                    $this->isChanged = true;
                }
            }
        }
        $this->save();
    }

    /**
     * @param array|string $keyword
     * @return void
     */
    public function delete($keyword)
    {
        if (is_string($keyword)) {
            $keyword = [$keyword];
        }
        if (is_array($keyword)) {
            foreach ($keyword as $word) {
                foreach ($this->_keywords as $k => $v) {
                    if (strcasecmp($v['w'], $word) === 0) {
                        unset($this->_keywords[$k]);
                        $this->isChanged = true;
                        break;
                    }
                }
            }
        }
        $this->save();
    }

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword)
    {
        $have = false;
        foreach ($this->_keywords as $v) {
            if (strcasecmp($v['w'], $keyword) === 0) {
                $have = true;
                break;
            }
        }
        return $have;
    }

    /**
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return void
     */
    protected function save()
    {
        if ($this->isChanged) {
            $this->_keywords = array_filter($this->_keywords, function ($keyword) {
                return trim($keyword['w']) != '';
            });
            $column = array_column($this->_keywords, 'l');
            array_multisort($column, SORT_ASC, $this->_keywords);
            $this->keywords = array_map('current', $this->_keywords);
            $fp = fopen($this->file, 'w');
            flock($fp, LOCK_EX);
            foreach ($this->_keywords as $v)
                $len = fwrite($fp, $v['w'] . "\n");
            flock($fp, LOCK_UN);
            fclose($fp);
            $this->isChanged = false;
        }
    }

    /**
     * @param string $keyword
     * @return int
     */
    public function countKeyword($keyword)
    {
        return mb_strlen($keyword);
    }

}