<?php

if(isset($_GET['filter'])) {

}

$alphabet = array( "a" , "b" , "c" , "d" , "e" , "f" , "g" , "h" , "i" , "j" , "k" , "l" , "m" , "n" , "o" , "p" , "q" , "r" , "s" , "t" , "u" , "v" , "w" , "x" , "y" , "z");
$data_dir = ".." . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "dictionary";

if(!is_dir($data_dir)) {
    	@mkdir($data_dir , "0777" , true);
	@chmod($data_dir , "0777");
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
		@mkdir($path , "0777" , true);
		@chmod($path , "0777");
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
			@chmod($path_second , "0777");
			//@chuser($path_second , "fronter");
			//@chgrp($path_second , "fronter");
		}

		saveContent($full_char . "*" , $file_path);
	}
}
echo "Done";

function saveContent($characters , $file_path) {
	$json_output = file_get_contents("http://api.pearson.com/longman/dictionary/entry.xml?q=" . $characters . "&apikey=bcf1607a604d5a45d78dfc018857e901&jsonp=xml");
        $file = fopen($file_path , "w");
        fwrite($file , $json_output);
	@chmod($file_path , "0777");
        fclose($file);
}
?>
