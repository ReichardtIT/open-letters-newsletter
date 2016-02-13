<?php
/**
 * Diese Datei stellt eine Klasse fuer den Datenbankzugriff bereit.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

    /** Elternklasse laden */
    require_once( ROOT_PATH . "framework/logable.class.php");

/**
 * Diese Klasse erlaubt einfach zu verwendenden DB-Zugriff.
 * @author Sebastian de Vries, Benjamin Moll und Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class Db extends Logable {

    /** die Verbindung zur Datenbank selbst */
    public static $dbHandle;

    /** Objekt der Klasse <i>Text</i> */
    protected $text;

    /**
     * Der Konstruktor erzeugt eine neue Datenbankverbindung. Dies tut er nur
     * einmal, denn das DB-Handle wird in einem statischen Attribut gespeichert.
     */
    public function __construct() {
        if (is_null(self::$dbHandle)) {
            self::$dbHandle = @mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
            if (!self::$dbHandle) {
                $this->logmessage("No db connection!");
            } else {
                if (!mysql_select_db(DB_NAME, self::$dbHandle)) {
                    $this->logmessage("No database selected!");
                }
                mysql_set_charset("utf8");
            }
        }
    }

    /**
     * Diese Methode prueft, ob in der uebergebenen DB-Tabelle eine Spalte des angegebenen Namens existiert.
     * @param String $table_name
     * @param String $column_name
     * @return int 1 wenn eine Spalte dieses Namens in einer Tabelle des Namens existiert, sonst 0
     */
    public function column_exists($table_name, $column_name) {
        $rueckgabe = 0;

        if ($this->table_exists($table_name)) {
            $sql = "DESCRIBE " . PREFIX . $table_name;
            $erg = $this->query($sql);

            for ($i = 0; $i < sizeof($erg); $i++) {
                if ($erg[$i]['Field'] == $column_name) {
                    $rueckgabe = 1;
                    break;
                }
            }
        }

        return( $rueckgabe);
    }

    /**
     * Diese Methode gibt die in der letzten INSERT-Query automatisch erzeugten
     * ID zurueck.
     * @return int
     */
    public function get_last_insert_id() {
        $rueckgabe = @mysql_insert_id(self::$dbHandle);
        return( $rueckgabe);
    }

    /**
     * Diese Methode entschaerft den uebergebenen Text so, dass er ohne Bedenken
     * in ein SQL-Statement eingefuegt werden kann. Dabei wird auch auf evtl.
     * aktivierte <i>magic_quotes</i> Ruecksicht genommen.
     * @param string $text der zu bearbeitende Text
     * @return string der bearbeitete Text
     */
    public function prepare_for_db($text) {
        $rueckgabe = "";
        if (!self::$dbHandle)
            return( $rueckgabe);

        $text = strip_tags($text);
        if (ini_get('magic_quotes_gpc') == "1")
            $text = stripslashes($text);

        $rueckgabe = mysql_real_escape_string($text, self::$dbHandle);

        return( $rueckgabe);
    }

    /**
     * Fuehrt eine Abfrage an den MySQL-Server aus
     * @param string $string Die auszufuehrende Anfrage
     * @param int $disable_logmessage wird 1 uebergeben, so wird bei einem auftretenden
     * Fehler keine Lomessage in die Logdatei abgesetzt
     * @return array Das Ergebnis der Anfrage als Array von Werten oder null im Fehlerfall
     */
    function query($string, $disable_logmessage = 0) {
        $resArray = array();
        if (!self::$dbHandle)
            return( $resArray);

        $i = 0;
        $resId = mysql_query($string, self::$dbHandle);

        if (( @mysql_errno(self::$dbHandle) != 0 ) && ( @mysql_error(self::$dbHandle) != "" )) {
            if ($disable_logmessage != 1)
                $this->logmessage("Fehler bei Db-Anfrage: " . $string);
            $resArray = null;
        }else {
            while ($tmp = @mysql_fetch_array($resId)) {
                $resArray[$i] = $tmp;
                $i++;
            }
        }

        return $resArray;
    }

    /**
     * Diese Methode prueft, ob in der DB eine Tabelle des uebergebenen Namens existiert.
     * @param String $tablename der Name der gesuchten DB-Tabelle
     * @return int 1 wenn die Tabelle gefunden wurde, sonst 0
     */
    public function table_exists($tablename) {
        $rueckgabe = 0;

        $sql = "SHOW TABLES";
        $erg = $this->query($sql);

        for ($i = 0; $i < sizeof($erg); $i++) {
            if ($erg[$i]['Tables_in_' . DB_NAME] == PREFIX . $tablename) {
                $rueckgabe = 1;
                break;
            }
        }

        return( $rueckgabe);
    }
    
    /**
     * Adds a database column (without checking first if column exists).
     * @param string $tablename name of database table (without PREFIX)
     * @param string $column_name name of database table column
     * @param string $column_spec SQL column definition like: TINYINT(1) NOT NULL DEFAULT '0'
     * @return boolean returns true on success
     * @throws PDOException if an error occurs
     */
    public function add_column( $tablename, $column_name, $column_spec){
        
        $sql = $this->create_add_column_query($tablename, $column_name, $column_spec);
        if( !is_null( $this->query( $sql))){
            return true;
        }

        throw new Exception("Die Spalte '".$column_name."' in der DB-Tabelle '".$tablename."' konnte nicht angelegt werden!"
            . "MySQL-Error ".$this->get_last_error());
    }

    /**
     * get last mysql error code and message from DB connection
     * @return string
     */
    public function get_last_error(){
        return mysql_errno(self::$dbHandle) . ": " . mysql_error(self::$dbHandle);
    }

    /**
     * 
     * @param type $tablename
     * @param type $column_name
     * @param type $column_spec
     * @return string
     */
    protected function create_add_column_query( $tablename, $column_name, $column_spec){
        
        return "ALTER TABLE ".PREFIX.$tablename." ADD ".$column_name." ".$column_spec.";";
    }
}
