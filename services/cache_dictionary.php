<?php
include "config.php";

$alphabet = array( "a" , "b" , "c" , "d" , "e" , "f" , "g" , "h" , "i" , "j" , "k" , "l" , "m" , "n" , "o" , "p" , "q" , "r" , "s" , "t" , "u" , "v" , "w" , "x" , "y" , "z");
//$data_dir = ".." . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "dictionary";

if(!is_dir($data_dir)) {
    @mkdir($data_dir , "0777" , true);
    exec("sudo chmod 0777 -R " . $data_dir . "/../");
}

// Check there is a argument(search word) in service execute command, then create folder, xml for that specific word.
// Else, full caching will be execute.
if(isset($argv[1])) {
    $first_char = substr($argv[1] , 0 ,1);
    $second_char = substr($argv[1] , 1 ,1);
    $full_char = "";
    
    if($second_char) {
        $full_char = $first_char . $second_char;
        $data_dir = $data_dir . DIRECTORY_SEPARATOR . $first_char . DIRECTORY_SEPARATOR . $full_char;
        $path_suffix = $full_char . ".xml";
    } else {
        $data_dir = $data_dir . DIRECTORY_SEPARATOR . $first_char;
        $path_suffix = $first_char . ".xml";
    }

    if(!is_dir($data_dir)) {
        @mkdir($data_dir , "0777" , true);
        exec("sudo chmod 0777 -R " . $data_dir . "/../");
    }
    
    $file_path = $data_dir . DIRECTORY_SEPARATOR . $path_suffix;
    
    saveContent($full_char . "*" , $file_path);
    
} else {

    /*
      Loop the alphabet array, create seperate folders for first character and second character.
      Ex:- a / aa
	     / ab
	     / ac ... /az
           b / ba
	     / bb ... /bz

      Save dictionary data as xml files inside folders
    */
    foreach($alphabet as $char) {
	    $path = $data_dir . DIRECTORY_SEPARATOR . $char;
	    if(!is_dir($path)) {
		    @mkdir($path , "0777" , true);
		    exec("sudo chmod 0777 -R " . $path . "/../");
		    //@chuser($path , "fronter");
		    //@chgrp($path , "fronter");
	    }
        
	    $file_path = $path . DIRECTORY_SEPARATOR . $char . ".xml";
	    saveContent($char , $file_path);

	    foreach($alphabet as $second_char) {
		    $full_char = $char . $second_char;
		    $path_second = $path . DIRECTORY_SEPARATOR . $full_char;
		    $file_path = $path_second . DIRECTORY_SEPARATOR . $full_char . ".xml";

		    if(!is_dir($path_second)) {
	        	@mkdir($path_second , "0777" , true);
			    exec("sudo chmod 0777 -R " . $path_second . "/../");
			    //@chuser($path_second , "fronter");
			    //@chgrp($path_second , "fronter");
		    }

		    saveContent($full_char . "*" , $file_path);
	    }
    }
    echo "Done";

}

function saveContent($characters , $file_path) {
    global $apikey;
	$json_output = file_get_contents("http://api.pearson.com/longman/dictionary/entry.xml?q=" . $characters . "&apikey=" . $apikey . "&jsonp=xml");
    $file = fopen($file_path , "w");
    fwrite($file , $json_output);
	exec("sudo chmod 0777 " . $file_path);
    fclose($file);
}
?>
