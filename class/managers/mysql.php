<?php

class DBM
{
	private $debug = false;

	private static $instance;
	private $link;
	private $host;
	private $user;
	private $pass;
	private $db;
	private $quest;
	private $result = array();
	private $numResults;

	private function __construct()
	{
		$this->pass = '';
		$this->host = 'localhost';
		$this->user = 'root';
		$this->db = 'echoes_game';
		
	}

	public function __destruct()
	{

	}

	public static function get()
	{
		if (!isset(self::$instance))
		{
				self::$instance = new DBM();
		}
		return self::$instance;
	}

	public function connect()
	{
		// I had to add an @ beacause the method is deprecated and the xml answer is not well interpreted when starting with WARNING =]
		// I did that to be able to put a working code on Github as fast as possible, of course I will improve that
		$this->link = @mysql_connect($this->host, $this->user, $this->pass) or die (mysql_error());
		mysql_select_db($this->db);
		return $this->link;
	}

	public function disconnect()
	{
		if ($this->link)
		{
			mysql_close($this->link);
			$this->link = "";
		}
	}

	public function query($query)
	{
		if (! $this->link)
				$this->connect();
		$this->quest =  mysql_query($query, $this->link) or die (mysql_error());
		return $this->quest;
	}

	public function asArray($quest)
	{
		return mysql_fetch_array($quest);
	}

	public function asObject($quest)
	{
		return mysql_fetch_object($quest);
	}

	public function nbResult()
	{
		if ($this->quest != NULL)
			return mysql_num_rows($this->quest);
		return 0;
	}
	
	public function getUniqueValue()
	{
		if ($this->quest != NULL)
		{
			$result = $this->asArray($this->quest);
			return $result[0];
		}
		return 0;
	}
	

	/*
    * Checks to see whether the table exists when performing
    * queries
    */
    private function tableExists($table)
    {
		if (! $this->link)
		{
			$this->connect();
		}
		$request = 'SHOW TABLES FROM '.$this->db.' LIKE "'.$table.'"';
		//print_r($request);
        $tablesInDb = mysql_query($request);
        if($tablesInDb)
        {
            if(mysql_num_rows($tablesInDb)==1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
	
	/*
    * Selects information from the database.
    * Required: table (the name of the table)
    * Optional: rows (the columns requested, separated by commas)
    *           where (column = value as a string)
    *           order (column DIRECTION as a string)
    */
    public function select($table, $rows = '*', $where = null, $order = null, $group=null)
    {
		if (! $this->link)
		{
			$this->connect();
		}
		$this->result = "";
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
		if($group != null)
            $q .= ' GROUP BY '.$group;
        if($order != null)
            $q .= ' ORDER BY '.$order;
		
		//print_r($q);
        $this->quest = mysql_query($q);
		
		
		/*$monfichier = fopen('../class/managers/debug.txt', 'a+');
		 
		fseek($monfichier, 0);
		fputs($monfichier, $q.PHP_EOL); 
		 
		fclose($monfichier);*/
		 

        if($this->quest)
        {
            $this->numResults = mysql_num_rows($this->quest);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($this->quest);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    // Sanitizes keys so only alphavalues are allowed
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($this->quest) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($this->quest) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
        	echo "Erreur lors de l'utilisation de la méthode select.";
			echo "<br>".$q;
            return false;
        }
    }



/*
    * Insert values into the table
    * Required: table (the name of the table)
    *           values (the values to be inserted)
    * Optional: rows (if values don't match the number of rows)
    */
    public function insert($table,$values,$rows = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }

            for($i = 0; $i < count($values); $i++)
            {
                if(is_string($values[$i]))
                    $values[$i] = '"'.$values[$i].'"';
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
            $ins = mysql_query($insert);

            if($ins)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
		else
		{
			print_r("La table '".$table."' n'existe pas.");
		}
    }

    /*
    * Deletes table or records where condition is true
    * Required: table (the name of the table)
    * Optional: where (condition [column =  value])
    */
    public function delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = mysql_query($delete);

            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Updates the database with the values sent
     * Required: table (the name of the table to be updated
     *           rows (the rows/values in a key/value array
     *           where (the row/condition in an array (row,condition) )
     */
    public function update($table,$rows,$where,$caseSensitive = false)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            if ($caseSensitive)
			{
				$upper1 = "UPPER(";
				$upper2 = ")";
			}
			else
			{
				$upper1 = "";
				$upper2 = "";
			}
            for($i = 0; $i < count($where); $i++)
            {
                if($i%2 != 0)
                {
                    if(isset($where[$i+1]))
					{
                        $where[$i] = '= '.$upper1.'"'.$where[$i].'"'.$upper2.' AND ';
					}
                    else
					{
                        $where[$i] = '= '.$upper1.'"'.$where[$i].'"'.$upper2.'';
					}
                }
            }
            $where = implode('',$where);


            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++)
            {
                $update .= $keys[$i].'='.$rows[$keys[$i]];
                // Parse to add commas
                if($i != count($rows)-1)
                {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;
			if ($this->debug)
			{
				print_r($update);
			}
            $query = mysql_query($update);
            if($query)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
    * Returns the result set
    */
    public function getQuest()
    {
        return $this->quest;
    }

    public function getResult()
    {
        return $this->result;
    }
}
/*
$test = DBM::get();
$test->connect();
$quest = $test->query('SELECT * FROM `utilisateurs` WHERE 1');
echo mysql_num_rows($quest);
 */
?>