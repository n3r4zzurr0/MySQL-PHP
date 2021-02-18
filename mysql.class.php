class MySQL {

	public $conn;

	function __construct($connection)
	{
		$this->conn = $connection;
	}

	function begin($level)
	{
		$exec = true;

		if($exec)
		{
			$sql_command = 'SET TRANSACTION ISOLATION LEVEL '.$level;
			$sql = $this->conn -> prepare($sql_command);
			$exec = $sql -> execute();

			return $exec;
		}
		else
		{
			return false;
		}
	}

	function commit()
	{
		$sql_command = 'COMMIT';
		$sql = $this->conn -> prepare($sql_command);
		$exec = $sql -> execute();

		return $exec;
	}

	function rollback()
	{
		$sql_command = 'ROLLBACK';
		$sql = $this->conn -> prepare($sql_command);
		$exec = $sql -> execute();

		return $exec;
	}

	function put($values, $table)
	{
		$sql_command = 'INSERT INTO '.$table.' ';

		$i = 0;
		$is_assoc = 0;

		foreach($values as $key => $value)
		{
			if($key != $i)
			{
				$is_assoc = 1;
				break;
			}
			$i ++;
		}

		if($is_assoc)
		{
			$key_string = '(';

			foreach($values as $key => $value)
			{
				$key_string .= $key . ', ';
			}
			$key_string = substr($key_string, 0, strlen($key_string) - 2) . ') ';
			$sql_command .= $key_string;
		}
		$sql_command .= 'VALUES';

		$i = 0;
		foreach($values as $key => $value)
		{
			$escape_data[$i] = $value;
			$escape_string[$i] = '?';
			$i ++;
		}
		$escape_string = implode(', ', $escape_string);

		$sql = $this->conn -> prepare($sql_command.' ('.$escape_string.')');

		$exec = $sql -> execute($escape_data);

		return $exec;
	}

	function get($column, $table, $conditions, $order, $limit)
	{
		$sql_command = 'SELECT '.$column.' FROM '.$table.' WHERE ';

		$i = 0;
		foreach ($conditions as $key => $value)
		{
			if(substr($value, 0, 2) === '<=')
			{
				$escape_data[$i] = str_replace('<=', '', $value);
				$escape_string[$i] = $key.' <= ?';
			}
			else if(substr($value, 0, 2) === '>=')
			{
				$escape_data[$i] = str_replace('>=', '', $value);
				$escape_string[$i] = $key.' >= ?';
			}
			else if(substr($value, 0, 1) === '<')
			{
				$escape_data[$i] = str_replace('<', '', $value);
				$escape_string[$i] = $key.' < ?';
			}
			else if(substr($value, 0, 1) === '>')
			{
				$escape_data[$i] = str_replace('>', '', $value);
				$escape_string[$i] = $key.' > ?';
			}
			else
			{
				$escape_data[$i] = $value;
				$escape_string[$i] = $key.' = ?';
			}
			$i ++;
		}
		$escape_string = implode(' AND ', $escape_string);

		$order_by_string = '';
		if($order != -1)
		{
			foreach ($order as $key => $value)
			{
				$order_by_string = ' ORDER BY '.$key.' '.$value;
			}
		}
		
		$limiting_string = $limit != -1 ? ' LIMIT '.$limit : '';

		$sql = $this->conn -> prepare($sql_command . $escape_string . $order_by_string . $limiting_string);
		$exec = $sql -> execute($escape_data);

		$response = array();

		if($exec)
		{
			$i = 0;
			if($column != '*')
			{
				foreach($sql as $row)
				{
					$response[$i] = $row[$column];
					$i ++;
				}
			}
			else
			{
				foreach($sql as $row)
				{
					$response[$i] = $row;
					$i ++;
				}
			}
		}

		return $response;
	}

	function set($values, $table, $conditions)
	{
		$sql_command = 'UPDATE '.$table.' SET ';

		foreach ($values as $col => $val)
		{
			$sql_command .= $col.' = ?, '; 
		}
		$sql_command = substr($sql_command, 0, strlen($sql_command) - 2).' WHERE ';

		$i = 0;
		foreach ($values as $col => $val)
		{
			$escape_data[$i] = $val;
			$i ++;
		}
		foreach ($conditions as $key => $value)
		{
			$escape_data[$i] = $value;
			$escape_string[$i] = $key.' = ?';
			$i ++;
		}
		$escape_string = implode(' AND ', $escape_string);

		$sql = $this->conn -> prepare($sql_command . $escape_string);
		$exec = $sql -> execute($escape_data);

		return $exec;
	}

	function del($table, $conditions)
	{
		$sql_command = 'DELETE FROM '.$table.' WHERE ';

		$i = 0;
		foreach ($conditions as $key => $value)
		{
			$escape_data[$i] = $value;
			$escape_string[$i] = $key.' = ?';
			$i ++;
		}
		$escape_string = implode(' AND ', $escape_string);

		$sql = $this->conn -> prepare($sql_command . $escape_string);
		$exec = $sql -> execute($escape_data);

		return $exec;
	}
}
