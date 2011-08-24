<?php

require_once("request.php");

class ScoresManager
{
	private $request;
	private static $instance;

	private function __construct()
	{
		$this->request = Request::get();
	}

	public function __destruct()
	{

	}

	public static function get()
	{
		if (!isset(self::$instance))
		{
				self::$instance = new ScoresManager();
		}
		return self::$instance;
	}

	public function lost($id,$level)
	{
		$this->request->updateScoresBest($id,$level);
	}

	public function win($id,$level,$nbClic,$prtSatisfied)
	{
		switch (strtolower($level)) 
		{
			case 'novice':
				$base[0] = 1000;
				$base[1] = 20;
			    break;
			case 'normal':
				$base[0] = 5000;
				$base[1] = 50;
			    break;
			case 'expert':
				$base[0] = 10000;
				$base[1] = 80;
			    break;
			case 'maitre':
				$base[0] = 30000;
				$base[1] = 150;
			    break;
			default:
				return false;
				break;
		}
		$bonusClick = $base[0] - ($base[1] * $nbClic);
		if ($bonusClick < 0)
		{
			$bonusClick = 0;
		}
		$score = $base[0] + $bonusClick + ($prtSatisfied * $base[0]) / 100;
		$this->request->updateScoresBest($id,$level,true,$score,$nbClic,$prtSatisfied);
		$this->request->updateScoresRace($id,$score/10);
	}
}

?>