<?php namespace Hwj;
/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

my_defined('JPATH_PLATFORM') or die;

/**
 * Oracle database driver
 *
 * @package     Joomla.Platform
 * @subpackage  Database
 * @see         http://php.net/pdo
 * @since       12.1
 */
class JDatabaseDriverOracle extends JDatabaseDriverPdo
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  12.1
	 */
	public $name = 'oracle';

	/**
	 * The character(s) used to quote SQL statement names such as table names or field names,
	 * etc.  The child classes should define this as necessary.  If a single character string the
	 * same character is used for both sides of the quoted name, else the first character will be
	 * used for the opening quote and the second for the closing quote.
	 *
	 * @var    string
	 * @since  12.1
	 */
	protected $nameQuote = '"';

	/**
	 * Returns the current dateformat
	 *
	 * @var   string
	 * @since 12.1
	 */
	protected $dateformat;

	/**
	 * Returns the current character set
	 *
	 * @var   string
	 * @since 12.1
	 */
	protected $charset;

	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @since   12.1
	 */
	public function __construct($options)
	{
		$options['driver'] = 'oci';
		$options['charset']    = (isset($options['charset'])) ? $options['charset']   : 'AL32UTF8';
		$options['dateformat'] = (isset($options['dateformat'])) ? $options['dateformat'] : 'RRRR-MM-DD HH24:MI:SS';

		$this->charset = $options['charset'];
		$this->dateformat = $options['dateformat'];

		// Finalize initialisation
		parent::__construct($options);
	}

	/**
	 * Destructor.
	 *
	 * @since   12.1
	 */
	public function __destruct()
	{
		$this->freeResult();
		unset($this->connection);
	}

	/**
	 * Connects to the database if needed.
	 *
	 * @return  void  Returns void if the database connected successfully.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function connect()
	{
		if ($this->connection)
		{
			return;
		}

		parent::connect();

		if (isset($this->options['schema']))
		{
			$this->setQuery('ALTER SESSION SET CURRENT_SCHEMA = ' . $this->quoteName($this->options['schema']))->execute();
		}

		$this->setDateFormat($this->dateformat);
	}

	/**
	 * Disconnects the database.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function disconnect()
	{
		// Close the connection.
		$this->freeResult();
		unset($this->connection);
	}

	/**
	 * Drops a table from the database.
	 *
	 * Note: The IF EXISTS flag is unused in the Oracle driver.
	 *
	 * @param   string   $tableName  The name of the database table to drop.
	 * @param   boolean  $ifExists   Optionally specify that the table must exist before it is dropped.
	 *
	 * @return  JDatabaseDriverOracle  Returns this object to support chaining.
	 *
	 * @since   12.1
	 */
	public function dropTable($tableName, $ifExists = true)
	{
		$this->connect();

		$query = $this->getQuery(true);

		$query->setQuery('DROP TABLE :tableName');
		$query->bind(':tableName', $tableName);

		$this->setQuery($query);

		$this->execute();

		return $this;
	}

	/**
	 * Method to get the database collation in use by sampling a text field of a table in the database.
	 *
	 * @return  mixed  The collation in use by the database or boolean false if not supported.
	 *
	 * @since   12.1
	 */
	public function getCollation()
	{
		return $this->charset;
	}

	/**
	 * Get a query to run and verify the database is operational.
	 *
	 * @return  string  The query to check the health of the DB.
	 *
	 * @since   12.2
	 */
	public function getConnectedQuery()
	{
		return 'SELECT 1 FROM dual';
	}

	/**
     * Returns the current date format
     * This method should be useful in the case that
     * somebody actually wants to use a different
     * date format and needs to check what the current
     * one is to see if it needs to be changed.
     *
     * @return string The current date format
     *
     * @since 12.1
     */
	public function getDateFormat()
	{
		return $this->dateformat;
	}

	/**
	 * Shows the table CREATE statement that creates the given tables.
	 *
	 * Note: You must have the correct privileges before this method
	 * will return usable results!
	 *
	 * @param   mixed  $tables  A table name or a list of table names.
	 *
	 * @return  array  A list of the create SQL for the tables.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function getTableCreate($tables)
	{
		$this->connect();

		$result = array();
		$query = $this->getQuery(true);

		$query->select('dbms_metadata.get_ddl(:type, :tableName)');
		$query->from('dual');

		$query->bind(':type', 'TABLE');

		// Sanitize input to an array and iterate over the list.
		settype($tables, 'array');
		foreach ($tables as $table)
		{
			$query->bind(':tableName', $table);
			$this->setQuery($query);
			$statement = (string) $this->loadResult();
			$result[$table] = $statement;
		}

		return $result;
	}

	/**
	 * Retrieves field information about a given table.
	 *
	 * @param   string   $table     The name of the database table.
	 * @param   boolean  $typeOnly  True to only return field types.
	 *
	 * @return  array  An array of fields for the database table.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function getTableColumns($table, $typeOnly = true)
	{
		$this->connect();

		$columns = array();
		$query = $this->getQuery(true);

		$fieldCasing = $this->getOption(PDO::ATTR_CASE);

		$this->setOption(PDO::ATTR_CASE, PDO::CASE_UPPER);

		$table = strtoupper($table);

		$query->select('*');
		$query->from('ALL_TAB_COLUMNS');
		$query->where('table_name = :tableName');

		$prefixedTable = str_replace('#__', strtoupper($this->tablePrefix), $table);
		$query->bind(':tableName', $prefixedTable);
		$this->setQuery($query);
		$fields = $this->loadObjectList();

		if ($typeOnly)
		{
			foreach ($fields as $field)
			{
				$columns[$field->COLUMN_NAME] = $field->DATA_TYPE;
			}
		}
		else
		{
			foreach ($fields as $field)
			{
				$columns[$field->COLUMN_NAME] = $field;
				$columns[$field->COLUMN_NAME]->Default = null;
			}
		}

		$this->setOption(PDO::ATTR_CASE, $fieldCasing);

		return $columns;
	}

	/**
	 * Get the details list of keys for a table.
	 *
	 * @param   string  $table  The name of the table.
	 *
	 * @return  array  An array of the column specification for the table.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function getTableKeys($table)
	{
		$this->connect();

		$query = $this->getQuery(true);

		$fieldCasing = $this->getOption(PDO::ATTR_CASE);

		$this->setOption(PDO::ATTR_CASE, PDO::CASE_UPPER);

		$table = strtoupper($table);
		$query->select('*');
		$query->from('ALL_CONSTRAINTS');
		$query->where('table_name = :tableName');

		$query->bind(':tableName', $table);

		$this->setQuery($query);
		$keys = $this->loadObjectList();

		$this->setOption(PDO::ATTR_CASE, $fieldCasing);

		return $keys;
	}

	/**
	 * Method to get an array of all tables in the database (schema).
	 *
	 * @param   string   $databaseName         The database (schema) name
	 * @param   boolean  $includeDatabaseName  Whether to include the schema name in the results
	 *
	 * @return  array    An array of all the tables in the database.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function getTableList($databaseName = null, $includeDatabaseName = false)
	{
		$this->connect();

		$query = $this->getQuery(true);

		$tables = array();

		if ($includeDatabaseName)
		{
			$query->select('owner, table_name');
		}
		else
		{
			$query->select('table_name');
		}

		$query->from('all_tables');
		if ($databaseName)
		{
			$query->where('owner = :database');
			$query->bind(':database', $databaseName);
		}

		$query->order('table_name');

		$this->setQuery($query);

		if ($includeDatabaseName)
		{
			$tables = $this->loadAssocList();
		}
		else
		{
			$tables = $this->loadResultArray();
		}

		return $tables;
	}

	/**
	 * Get the version of the database connector.
	 *
	 * @return  string  The database connector version.
	 *
	 * @since   12.1
	 */
	public function getVersion()
	{
		$this->connect();

		$this->setQuery("select value from nls_database_parameters where parameter = 'NLS_RDBMS_VERSION'");
		return $this->loadResult();
	}

	/**
	 * Select a database for use.
	 *
	 * @param   string  $database  The name of the database to select for use.
	 *
	 * @return  boolean  True if the database was successfully selected.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function select($database)
	{
		$this->connect();

		return true;
	}

	/**
     * Sets the Oracle Date Format for the session
     * Default date format for Oracle is = DD-MON-RR
     * The default date format for this driver is:
     * 'RRRR-MM-DD HH24:MI:SS' since it is the format
     * that matches the MySQL one used within most Joomla
     * tables.
     *
     * @param   string  $dateFormat  Oracle Date Format String
     *
     * @return boolean
     *
     * @since  12.1
     */
	public function setDateFormat($dateFormat = 'DD-MON-RR')
	{
		$this->connect();

		$this->setQuery("ALTER SESSION SET NLS_DATE_FORMAT = '$dateFormat'");
		$this->setQuery("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = '$dateFormat'");
		if (!$this->execute())
		{
			return false;
		}

		$this->dateformat = $dateFormat;

		return true;
	}

	/**
	 * Set the connection to use UTF-8 character encoding.
	 *
	 * Returns false automatically for the Oracle driver since
	 * you can only set the character set when the connection
	 * is created.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   12.1
	 */
	public function setUTF()
	{
		return false;
	}

	/**
	 * Locks a table in the database.
	 *
	 * @param   string  $table  The name of the table to unlock.
	 *
	 * @return  JDatabaseDriverOracle  Returns this object to support chaining.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function lockTable($table)
	{
		$this->setQuery('LOCK TABLE ' . $this->quoteName($table) . ' IN EXCLUSIVE MODE')->execute();

		return $this;
	}

	/**
	 * Renames a table in the database.
	 *
	 * @param   string  $oldTable  The name of the table to be renamed
	 * @param   string  $newTable  The new name for the table.
	 * @param   string  $backup    Not used by Oracle.
	 * @param   string  $prefix    Not used by Oracle.
	 *
	 * @return  JDatabaseDriverOracle  Returns this object to support chaining.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function renameTable($oldTable, $newTable, $backup = null, $prefix = null)
	{
		$this->setQuery('RENAME ' . $oldTable . ' TO ' . $newTable)->execute();

		return $this;
	}

	/**
	 * Unlocks tables in the database.
	 *
	 * @return  JDatabaseDriverOracle  Returns this object to support chaining.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function unlockTables()
	{
		$this->setQuery('COMMIT')->execute();

		return $this;
	}

	/**
	 * Test to see if the PDO ODBC connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   12.1
	 */
	public static function isSupported()
	{
		return my_class_exists_d('PDO') && in_array('oci', PDO::getAvailableDrivers());
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the string held is the
	 * <var>tablePrefix</var> class variable.
	 *
	 * @param   string  $sql     The SQL statement to prepare.
	 * @param   string  $prefix  The common table prefix.
	 *
	 * @return  string  The processed SQL statement.
	 *
	 * @since   11.1
	 */
	public function replacePrefix($sql, $prefix = '#__')
	{
		$escaped = false;
		$startPos = 0;
		$quoteChar = "'";
		$literal = '';

		$sql = trim($sql);
		$n = strlen($sql);

		while ($startPos < $n)
		{
			$ip = strpos($sql, $prefix, $startPos);
			if ($ip === false)
			{
				break;
			}

			$j = strpos($sql, "'", $startPos);

			if ($j === false)
			{
				$j = $n;
			}

			$literal .= str_replace($prefix, $this->tablePrefix, substr($sql, $startPos, $j - $startPos));
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n)
			{
				break;
			}

			// Quote comes first, find end of quote
			while (true)
			{
				$k = strpos($sql, $quoteChar, $j);
				$escaped = false;
				if ($k === false)
				{
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\')
				{
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped)
				{
					$j = $k + 1;
					continue;
				}
				break;
			}
			if ($k === false)
			{
				// Error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr($sql, $startPos, $k - $startPos + 1);
			$startPos = $k + 1;
		}
		if ($startPos < $n)
		{
			$literal .= substr($sql, $startPos, $n - $startPos);
		}

		return $literal;
	}
}
