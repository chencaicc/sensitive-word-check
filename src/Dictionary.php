<?php
namespace Chencaicc\SensitiveWordCheck;

class Dictionary implements DictionaryInterface, \Iterator
{
    /**
     * @param array  反字典
     */
    public $backDictionary = [];

    private $position = 0;

    /**
     * @param bool
     */
    protected $needSave = false;

    /**
     * @param array  字典数据
     */
    protected $data = [];

    /**
     * @param string  字典文件路径
     */
    private $filePath = null;

    /**
     * @param string $filePath 文件路径
     * @throws 文件权限异常、系统异常
     */
    public function __construct($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("文件路径不存在！");
        }
        $this->filePath = $filePath;
        $this->data = $this->getFileData($filePath);
    }

    /**
     * @param string /array $keyword
     * @param object $otherObj 另一部字典[关键词不要出现在另一部字典里面，否则不可以添加]
     * @return bool
     * @throws 参数异常
     */
    public function addKeyword($keyword)
    {
        if (is_string($keyword)) {
            $keyword = trim($keyword);
            if ($keyword == '') {
                return false;
            }
            // 当前字典已经存在关键字
            foreach ($this->data as $k => $v) {
                if ($v['word'] === $keyword)
                    return true;
            }
            // 在另一部字典里面
            foreach ($this->backDictionary as $k => $v) {
                if ($v['word'] === $keyword)
                    return false;
            }
            $this->needSave = true;
            $this->data[] = ['word' => $keyword, 'len' => mb_strlen($keyword)];
            return true;
        } elseif (is_array($keyword)) {
            foreach ($keyword as $v) {
                $ok = $this->addKeyword($v);
            }
            return $ok;
        } else {
            throw new Exception("不支持的数据类型");
        }
    }

    /**
     * @param string /array $keyword
     * @param object $otherObj 另一部字典[关键词不要出现在另一部字典里面，否则不可以添加]
     * @return bool
     * @throws 参数异常
     */
    public function deleteKeyword($keyword)
    {
        if (is_string($keyword)) {
            $keyword = trim($keyword);
            if ($keyword == '') {
                return false;
            }
            // 当前字典已经存在关键字
            foreach ($this->data as $k => $v) {
                if ($v['word'] === $keyword) {
                    unset($this->data[$k]);
                    $this->needSave = true;
                    return true;
                }
            }
            // 不存在这个关键词，表示已经删除
            return true;
        } elseif (is_array($keyword)) {
            foreach ($keyword as $v) {
                $ok = $this->deleteKeyword($v);
            }
            return $ok;
        } else {
            throw new Exception("不支持的数据类型");
        }
    }

    /**
     * 保存数据到文件
     */
    public function __destruct()
    {
        // 如果操作了字典
        if ($this->needSave) {
            $this->saveData();
        }
    }

    /**
     * 排序并保存文件
     */
    private function saveData()
    {
        $sortArr = array_column($this->data, 'len');
        array_multisort($sortArr, SORT_ASC, $this->data);
        $this->writeFile($this->filePath, $this->data);
    }

    /**
     * @param string $file 保存的文件路径
     * @param array $data 保存的数据
     * @throws 文件权限异常
     */
    private function writeFile($file, $data)
    {
        $fp = fopen($file, 'w');
        flock($fp, LOCK_EX);
        foreach ($data as $v) {
            $len = fwrite($fp, $v['word'] . "\r\n");
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return $len;
    }

    /**
     * @param string $filePath 保存的文件路径
     * @param array  保存的数据
     * @throws 文件权限异常
     */
    private function getFileData($filePath)
    {
        $data = [];
        $fp = fopen($filePath, 'r+');
        while (!feof($fp)) {
            $word = trim(fgets($fp));
            if ($word == '') {
                continue;
            }
            $data[] = ['word' => $word, 'len' => mb_strlen($word)];
        }
        fclose($fp);
        return $data;
    }

    public function rewind()
    {
        return $this->position = 0;
    }

    public function current()
    {
        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    public function add($value)
    {
        $this->data[] = $value;
    }
}


