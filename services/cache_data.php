<?php

if(isset($_GET['filter'])) {

}

$alphabet = array( "a" , "b" , "c" , "d" , "e" , "f" , "g" , "h" , "i" , "j" , "k" , "l" , "m" , "n" , "o" , "p" , "q" , "r" , "s" , "t" , "u" , "v" , "w" , "x" , "y" , "z");
$data_dir = ".." . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "dictionary";

if(!is_dir($data_dir)) {
    @mkdir($data_dir);
    chmod($data_dir , "0777");
}

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
        @mkdir($path);
	chmod($path , "0777");
    }
    

    foreach($alphabet as $second_char) {
        $full_char = $char . $second_char;
        $path_second = $path . DIRECTORY_SEPARATOR . $full_char;
        $file_path = $path_second . DIRECTORY_SEPARATOR . $full_char . ".xml";
        
        if(!is_dir($path_second)) {
            @mkdir($path_second);
	    chmod($path_second , "0777");
        }
        
        $json_output = file_get_contents("http://api.pearson.com/longman/dictionary/entry.xml?q=" . $full_char . "*&apikey=3452afbefdf2978f76ffa7bae5f694e5&jsonp=xml");
        $file = fopen($file_path , "w");
        fwrite($file , $json_output);
	chmod($file , "0777");
        fclose($file);
    }
}
echo "Done";

?>
