<?php
/*
Server-side PHP file upload code for HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
/*
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
*/
//lee datos del archivo, para insertarlos en cada tabla

/*
//continúa con el proceso de carga de datos y extracción de estadísticas

// Load configuration as an array. Use the actual location of your configuration file
$config = parse_ini_file('conf/config.ini'); 

// Create connection
$conn = new mysqli($config['server'], $config['username'], $config['password'], $config['dbname']);
// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

debug_to_console(mysqli_connect_error());

// sql to create table
$sql = "CREATE TABLE bhsm_report (
date DATE, 
time VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table bhsm_report created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
*/

/*
recursos:
https://codereview.stackexchange.com/questions/92486/read-and-output-csv-content-oo-php
https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17

*/

class Db {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('conf/config.ini'); 
            self::$connection = new mysqli($config['server'], $config['username'], $config['password'], $config['dbname']);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }
	
	/**
     * Disconnect from the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
	 /*
	public function disconnect() {
		self::$connection= this -> close();
		
		return self::$connection;
	}
	*/

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query) {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }
}

class csvDoc {

    private $file; 

    public function __construct($filename, $mode) {
        $this->file = fopen($filename, $mode); 
        return $this->file;
    }

    public function endFile() {
        return feof($this->file); 
    }

    public function getCSV($mode) {
        return fgetcsv($this->file, $mode);
    } 

    public function close() {
        fclose($this->file); 
    }
}

/*
require('class.csvDoc.php');
require('class.Db.php');
*/

// Our database object
$db = new Db();

//csv object
$bhsm_rep = new csvDoc('uploads/BHSM_Report 0817 TEST.csv', 'r'); 

$fila = 1;
while(!$bhsm_rep->endFile()) {
	$datos = $bhsm_rep->getCSV(1024); 
    //echo $postcode[0] . "<br />"; 
	// Insert the values into the database
	$sql= "INSERT INTO bhsm_report VALUES ('".$datos[0]."', ".$datos[1].", ".$datos[2].", ".$datos[3].", ".$datos[4].", ".$datos[5].", ".$datos[6].", ".$datos[7].")";
	//echo $sql . "<br />"; 
	$result = $db -> query($sql);
	if($result === false) {
		echo "<p>línea $fila no insertada, error".$db -> error()." <br /></p>\n";
	} else {
		echo "<p>línea $fila si insertada <br /></p>\n";
	}
	$fila++;
}

$bhsm_rep->close();
//*/


/*
require('class.csv.php');

$csv = new CSV('postcodes.csv', 'r'); 

while(!$csv->endFile()) {

    $postcode = $csv->getCSV(1024); 
    echo $postcode[0] . "<br />"; 

}

$csv->close();
*/
/*
// Our database object
$db = new Db();

// Quote and escape form submitted values
$name = $db -> quote($_POST['username']);
$email = $db -> quote($_POST['email']);

// Insert the values into the database
$result = $db -> query("INSERT INTO `users` (`name`,`email`) VALUES (" . $name . "," . $email . ")");

//A select query:
$db = new Db();
$rows = $db -> select("SELECT `name`,`email` FROM `users` WHERE id=5");
*/