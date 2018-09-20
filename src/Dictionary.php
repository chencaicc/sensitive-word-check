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
    protected $keywords = [];

    /**
     * @var bool
     */
    protected $isChanged = false;

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
        $content = trim(file_get_contents($this->file));
        $this->keywords = $content ? explode("\n", $content) : [];
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
                    array_push($this->keywords, $value);
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
            foreach ($keyword as $value) {
                if ($this->exist($value)) {
                    $index = array_search($value, $this->keywords);
                    unset($this->keywords[$index]);
                    $this->isChanged = true;
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
        return in_array($keyword, $this->keywords);
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
            $this->keywords = array_filter($this->keywords, function ($keyword) {
                return trim($keyword) != '';
            });
            usort($this->keywords, function ($a, $b) {
                return mb_strlen($a) > mb_strlen($b);
            });
            file_put_contents($this->file, implode("\n", $this->keywords));
            $this->isChanged = false;
        }
    }

}