<?php

$filename = basename($_SERVER['REQUEST_URI']);

if($filename == "index.php" || $filename == "" || preg_match('/^\?/', $filename) || $filename == "game")
{
	require_once("./class/managers/mysql.php");
}
else
{
	require_once("../class/managers/mysql.php");
}

class Request
{
	private $sql;
	private static $instance;

	private function __construct()
	{
		$this->sql = DBM::get();
		$this->sql->connect();
	}

	public function __destruct()
	{

	}

	public static function get()
	{
		if (!isset(self::$instance))
		{
				self::$instance = new Request();
		}
		return self::$instance;
	}

	public function disconnect()
	{
		$this->sql->disconnect();
	}

	public function userExists($id)
	{
		$this->sql->select('USERS', '*', 'USR_ID = '.$id);
		if ($this->sql->nbResult() > 0)
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}
	
	public function updateUserIfNecessary($user)
	{
		$result = $this->sql->getResult();
		if (($result['USR_NAME'] != $user->name) ||
			($result['USR_FIRST_NAME'] != $user->first_name) ||
			($result['USR_LAST_NAME'] != $user->last_name) ||
			($result['USR_USERNAME'] != $user->username))
		{
			$values['USR_NAME'] = $user->name;
			$values['USR_FIRST_NAME'] = $user->first_name;
			$values['USR_LAST_NAME'] = $user->last_name;
			$values['USR_USERNAME'] = $user->username;
			
			$where[0] = 'USR_ID';
			$where[1] = $user->id;
			$this->sql->update('USERS',$values , $where);
		}
	}
	
	public function addUser($user)
	{
		$values[0] = $user->id;
		$values[1] = $user->name;
		$values[2] = $user->first_name;
		$values[3] = $user->last_name;
		$values[4] = $user->username;
		$this->sql->insert('USERS',$values);
		$this->initScoresBest($user->id);
		$this->initScoresRace($user->id);
	}

	/*
    * Init the best score for a user
    * Required: id (the facebook identifier) 
    */
	private function initScoresBest($id)
	{
		foreach(array('Novice','Normal','Expert','Maître') as $level)
		{
			$values[0] = $id;
			$values[1] = utf8_decode($level);
			for($i = 2; $i < 7; $i++)
			{
				$values[$i] = 0;
			}
			$this->sql->insert('SCORES_BEST',$values);
		}
	}

	/*
    * Init the scare score for a user
    * Required: id (the facebook identifier) 
    */
	private function initScoresRace($id)
	{
		$values[0] = $id;
		for($i = 1; $i < 3; $i++)
		{
			$values[$i] = 0;
		}
		$values[3] = 'Didacticiel';
		$this->sql->insert('SCORES_RACE',$values);
	}

	public function getScoresBest($id)
	{
		$this->sql->select('`SCORES_BEST`', '*', "`SBE_USR_ID` = '$id'");
		if ($this->sql->nbResult() > 0)
		{
			return $this->sql->getResult();
		}
		else 
		{
			return false;
		}
	}

	public function getScoresRace($id)
	{
		$this->sql->select('SCORES_RACE', '*', 'SRA_USR_ID = '.$id);
		if ($this->sql->nbResult() > 0)
		{
			return $this->sql->getResult();
		}
		else 
		{
			return false;
		}
	}

    /*
     * Updates the best scores
     * Required: id
     *           level
	 * Optional: win
	 *           score
	 *           nbClic
	 *           pourcent
     */
	public function updateScoresBest($id,$level,$win = false,$score = 0,$nbClic = 0,$pourcent = 0)
	{

		if ($id != '0')
		{
			$values['SBE_NB_PLAYED'] = 'SBE_NB_PLAYED + 1';
			// Si le joueur gagne
			if ($win)
			{
				$values['SBE_NB_WIN'] = 'SBE_NB_WIN + 1';
				//on récupère les anciennes valeurs
				$this->sql->select('SCORES_BEST', '*', 'SBE_USR_ID = '.$id .' AND UPPER(SBE_LEVEL)= UPPER("'.$level.'")');
				$result = $this->sql->getResult();
				//echo "score : " . $result['SBE_SCORE'] ."/".$score."<br>";
				if ($result['SBE_SCORE'] < $score)
				{
					$values['SBE_SCORE'] = $score;
				}
				//echo "nbClic : " . $result['SBE_NB_CLIC'] ."/".$nbClic."<br>";
				
				if (($result['SBE_NB_CLIC'] == 0) || ($result['SBE_NB_CLIC'] > $nbClic))
				{
					$values['SBE_NB_CLIC'] = $nbClic;
				}
				//echo "pourcent : " . $result['SBE_PERCENT_SATISF'] ."/".$pourcent."<br>";
				if ($result['SBE_PERCENT_SATISF'] < $pourcent)
				{
					$values['SBE_PERCENT_SATISF'] = $pourcent;
				}
			}
			
			
			$where[0] = 'SBE_LEVEL';
			$where[1] = $level;		
			$where[2] = 'SBE_USR_ID';
			$where[3] = $id;
			$this->sql->update('SCORES_BEST',$values , $where,true); 
		}
	}

    /*
     * Updates the race scores
     * Required: id
	 *           score
     */
	public function updateScoresRace($id,$score)
	{
		if ($id != '0')
		{
			$values['SRA_SCORE'] = 'SRA_SCORE + '.$score;
			$this->sql->select('SCORES_BEST', 'SBE_LEVEL', 'SBE_NB_PLAYED = 
																	(SELECT MAX( `SBE_NB_PLAYED` ) FROM `SCORES_BEST` WHERE SBE_USR_ID = '.$id.')
																			AND SBE_USR_ID = '.$id.' LIMIT 1');
			$res = $this->sql->getResult();
			$values['SRA_MOST_PLAYED_LEVEL']= "'".$res['SBE_LEVEL']."'";
			$where[0] = 'SRA_USR_ID';
			$where[1] = $id;
			$this->sql->update('SCORES_RACE',$values , $where); 
		}
	}

	public function topScoresBest($filtered)
	{
		$friendsIds = $this->getFriends();
		if ($filtered)
		{
			$where = '`SBE_USR_ID` IN (';
			foreach ($friendsIds as $id)
			{
				$where .= $id.',';
			}
			// FPO : C'est TSA qui m'a dit de faire ça.
			// TSA : et ouais ! et au final on remplace le 0 par l'id du joueur et c'est super utile ;o)
			$where .= $_SESSION['user']->id.') AND ';
		}
		else 
		{
			$where = "";
		}

		$where .= ' SBE_SCORE = ( 
			SELECT MAX(  `SBE_SCORE` ) 
			FROM SCORES_BEST
			WHERE SBE_LEVEL = s1.SBE_LEVEL ) ';
		
		$this->sql->select('SCORES_BEST s1', '`SBE_USR_ID`, `SBE_LEVEL`,MAX(`SBE_SCORE`) SBE_SCORE', $where, '`SBE_SCORE`,`SBE_USR_ID` DESC LIMIT 4', '`SBE_LEVEL`');
		if ($this->sql->nbResult() > 0)
		{
			return $this->sql->getResult();
		}
		else 
		{
			return false;
		}
	}
	
	public function getFriends()
	{
		$friendsIds = "";
		$i = 0;
		foreach ($_SESSION['friends']->data as $friend)
		{
			$friendsIds[$i] = $friend->id;
			$i++;
		}
		return $friendsIds;
	}
}

?>