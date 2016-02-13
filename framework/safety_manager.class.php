<?php
/**
 * Diese Datei enthält die Klasse SafetyManager.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */

    /** Elternklasse laden */
    require_once( ROOT_PATH . "framework/parentclass.class.php");

/**
 * Ein Objekt dieser Klasse nimmt einige grundlegende Prüfungen vor und stirbt
 * ggf. mit einer Fehlermeldung.
 * @author Stefan Rank-Kunitz at Open-Letters
 * @package Newslettersystem
 * @subpackage System
 */
class SafetyManager extends Parentclass{

    /**
     * Main-Methode der Klasse
     */
    public function process(){
        
        $user_ip = filter_input(INPUT_SERVER, "REMOTE_ADDR");

        // request comes from a whitelisted IP => abort checking
        if( in_array( $user_ip, array(
            "::1", // localhost in non-virtualized dev environments (IPv6)
        ))){
	    return true;
	}

	// request comes from a whitelisted network?
	$allowed_networks = array( "192", "10", "127");
	$user_ip_splitted = explode(".", $user_ip);
	if( array_key_exists(0, $user_ip_splitted) && in_array( $user_ip_splitted[0], $allowed_networks)){
	    return true;
	}


        $not_allowed_folders = array( "extras", "documentation");
        foreach( $not_allowed_folders as $folder){
            if( file_exists(ROOT_PATH.DIRECTORY_SEPARATOR.$folder)){
                die("Please delete folder ".realpath(ROOT_PATH).DIRECTORY_SEPARATOR
                    .$folder." before You use this software in production!!!");
            }
        }
    }
}
