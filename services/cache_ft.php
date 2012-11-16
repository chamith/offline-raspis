<?php
$data_dir = ".." . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "ft";

if(!is_dir($data_dir)) {
    @mkdir($data_dir , "0777");
}

$json_output = file_get_contents("http://api.pearson.com/ftpress/book.xml?apikey=3452afbefdf2978f76ffa7bae5f694e5&jsonp=xml");

//save all the books in to one xml file
$file_path = $data_dir . DIRECTORY_SEPARATOR . "books.xml";
$file = fopen($file_path , "w");
fwrite($file , $json_output);
chmod($file_path , "0777");
fclose($file);

//Read books.xml file, loop it to get full information of every book and save them to seperate xml files
$xml = simplexml_load_file($file_path);
foreach($xml->children() as $books){
	foreach($books->children() as $book => $data){
	  echo $data->id;
	  echo $data->title;
	  echo $data->author;
	  echo "<br />";
	}
}
?>
