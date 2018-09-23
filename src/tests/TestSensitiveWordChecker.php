<?php 
namespace Test;
use PHPUnit\Framework\TestCase;
use Chencaicc\SensitiveWordCheck\SensitiveWordChecker;
/*
	测试命令  ./vendor/bin/phpunit src/tests/TestSensitiveWordChecker

*/
class TestSensitiveWordChecker extends TestCase{
	/*
		test关键词添加到黑名单
	*/
	public function testAddToBlackList(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);
		$keyword = '黑词0';
		$ok = $checker->addToBlackList($keyword);
		$this->assertEquals(null,$ok);

		$keyword = [
			'黑词1',
			'黑词2',
			'黑词3',
			'黑词4',
			'黑词5',
			// new \splObjectStorage()//这里可以搞一个非法入参的错误哈
		];
		$ok = $checker->addToBlackList($keyword);
		$this->assertEquals(null,$ok);
	}
	/*
		test关键词从黑名单中去除
	*/
	public function testDeleteFromBlackList(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);
		$keyword = '黑词0';
		$ok = $checker->deleteFromBlackList($keyword);
		$this->assertEquals(null,$ok);

		$keyword = [
			'黑词3',
		];
		$ok = $checker->deleteFromBlackList($keyword);
		$this->assertEquals(null,$ok);
	}
	/*
		test关键词添加到白名单
	*/
	public function testAddToWhiteList(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);
		$keyword = '白词0';
		$ok = $checker->addToWhiteList($keyword);
		$this->assertEquals(null,$ok);

		$keyword = [
			'白词1',
			'白词2',
			'白词3',
			'白词4',
			'白词5',
		];
		$ok = $checker->addToWhiteList($keyword);
		$this->assertEquals(null,$ok);
	}
	/*
		test关键词从白名单中去除
	*/
	public function testDeleteFromWhiteList(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);
		$keyword = '白词0';
		$ok = $checker->deleteFromWhiteList($keyword);
		$this->assertEquals(null,$ok);
		$keyword = [
			'白词3',
		];
		$ok = $checker->deleteFromWhiteList($keyword);
		$this->assertEquals(null,$ok);
	}

	/*
		test非法的关键词
	*/
	public function testGetIllegalKeyword(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);

	}
	/*
		测试内容合法
	*/
	public function testIsValid(){
		$dir=__DIR__.'/db';
		$checker = new SensitiveWordChecker($dir);
		$content = '我的内容里面存在非法词---黑词4---';
		$ok = $checker->isValid($content);
		$this->assertEquals(null,$ok);

		$getWord = $checker->getIllegalKeyword();
		$this->assertEquals('黑词4',$getWord);
	}
}