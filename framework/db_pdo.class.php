<?php
/**
 * Diese Datei stellt eine Klasse fuer den Datenbankzugriff bereit.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

    /** Elternklasse laden */
    require_once( ROOT_PATH . "framework/db.class.php");

/**
 * Diese Klasse erlaubt einfach zu verwendenden DB-Zugriff.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class DbPdo extends Db {

    /** @var PDO die Verbindung zur Datenbank selbst */
    public static $dbHandle;
    
    /** @var array a list of error strings */
    protected static $errors;

    /**
     * Der Konstruktor erzeugt eine neue Datenbankverbindung. Dies tut er nur
     * einmal, denn das DB-Handle wird in einem statischen Attribut gespeichert.
     */
    public function __construct() {
        
        $this->_checkRequirements();
        
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_SERVER.';charset=UTF8';
        try {
            self::$dbHandle = new PDO($dsn, DB_USER, DB_PASSWORD);
            self::$errors = array();
            $this->_checkDbConnection();
        } catch (PDOException $e) {
            $this->logmessage('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Diese Methode gibt die in der letzten INSERT-Query automatisch erzeugten
     * ID zurueck.
     * @return int
     */
    public function get_last_insert_id( $parameters=null) {

        $this->_checkDbConnection();
        $rueckgabe = self::$dbHandle->lastInsertId();

        // fall back handling => find largest ID in database table
        if( $rueckgabe==0 && $parameters!==null){

            if( is_array( $parameters)){
                $sql = "SELECT max(".$parameters['id_column'].") FROM ".PREFIX.$parameters['table'].";";
                $tmp = $this->query($sql);
                if( is_array( $tmp) && count( $tmp)>0){
                    $rueckgabe = $tmp[0]['max('.$parameters['id_column'].')'];
                }
            }
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode ... tut nichts.
     * @param string $text der zu bearbeitende Text
     * @return string der unbearbeitete Text
     */
    public function prepare_for_db($text) {

        return( $text);
    }

    /**
     * Fuehrt eine Abfrage an den MySQL-Server aus
     * @param string $string Die auszufuehrende Anfrage
     * @param int $disable_logmessage wird 1 uebergeben, so wird bei einem auftretenden
     * Fehler keine Lomessage in die Logdatei abgesetzt
     * @return array Das Ergebnis der Anfrage als Array von Werten oder null im Fehlerfall
     */
    public function query($string, $disable_logmessage = 0) {
        $resArray = array();

        $this->_checkDbConnection();

        $i = 0;
        $resId = self::$dbHandle->query($string);

        if( $resId === FALSE) {
            if ($disable_logmessage != 1)
                $this->logmessage("Fehler bei Db-Anfrage: " . $string);
            $resArray = null;
        } else {
            foreach ($resId as $row) {
                $resArray[$i] = $row;
                $i++;
            }
        }

        return $resArray;
    }
    
    public function prepare_and_execute( $sql_query, $parameters=array()){

        $this->_checkDbConnection();
        
        $stmt = self::$dbHandle->prepare( $sql_query);
        $stmt->execute( $parameters);
        
        $result = false;
        $errors = $stmt->errorInfo();
        if( !is_array($errors) || intval($errors[0])==0){
            $result = $stmt->fetchAll();
        }else{
            $message = "Error executing SQL Query \"".$sql_query."\" with arguments (".
                implode(",", $parameters).") => Messages: ".  implode(",", $errors);
            $this->logmessage( $message);
            self::$errors[] = $message;
        }

        return( $result);
    }
    
    /**
     * function to render an SQL statement and return pointer to prepared statement
     * @param string $sql_query SQL query
     * @return PDOStatement
     */
    public function prepare( $sql_query){

        $this->_checkDbConnection();

        $stmt = self::$dbHandle->prepare( $sql_query);

        return $stmt;
    }

    /**
     * function to execute prepared statement with given values
     * @param PDOStatement $stmt
     * @param array $parameters an array of values
     * @return array
     */
    public function execute( $stmt, $parameters=array()){

        $stmt->execute( $parameters);

        $result = false;
        $errors = $stmt->errorInfo();
        if( !is_array($errors) || intval($errors[0])==0){
            $result = $stmt->fetchAll();
        }else{
            $message = "Error executing SQL Query \"".$sql_query."\" with arguments (".
                implode(",", $parameters).") => Messages: ".  implode(",", $errors);
            $this->logmessage( $message);
            self::$errors[] = $message;
        }

        return( $result);
    }

    protected function _checkDbConnection(){

        if( ! self::$dbHandle instanceof PDO){
            $message = "Error: PDO-Database connection can not be established. Please check Your DB configs in configuration file!";
            echo $message;
            self::$errors[] = $message;
            $this->logmessage($message);
            exit(1);
        }
    }
    
    protected function _checkRequirements(){

        if( ! class_exists("PDO")){
            $message = "Error: PHP class PDO required but not found!!! Please contact Your server admin to make this functionality available!";
            $this->logmessage($message);
            self::$errors[] = $message;
            echo $message;
            exit(1);
        }
    }
    
    public function add_column( $tablename, $column_name, $column_spec){
        
        $sql = $this->create_add_column_query($tablename, $column_name, $column_spec);
        if( is_array($this->prepare_and_execute( $sql))){
            return true;
        }

        throw new Exception("Die Spalte '".$column_name."' in der DB-Tabelle '".$tablename."' konnte nicht angelegt werden!"
            . " PDO-Error ".end( self::$errors));
    }
    
    public function get_last_error() {
        return end( self::$errors);
    }
}
