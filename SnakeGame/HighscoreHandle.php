<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars($_POST['name']);
  $score = htmlspecialchars($_POST['score']);

  if ((!empty($name))&&(!empty($score))) {
    add_table_entry($name,$score);
    write_table();
  }
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  write_table();
}

function add_table_entry($new_name, $new_score){
  // create new object:
  $new_obj->name = $new_name;
  $new_obj->score = $new_score;

  $objArray = get_scores_content();
  // convert to JSON object:
  if(count($objArray)>0){
    $new_entry = ',' . json_encode($new_obj) . ']';
  }else{
      $new_entry = '[' . json_encode($new_obj) . ']';
  }
  // write:
  if($fp = fopen("./scores.json", "c")){
    // go to EOF-1:
    fseek($fp, -1, SEEK_END);
    if(fwrite($fp, $new_entry)===false){
      echo 'error writing';
    }
    fclose($fp);
  } else{
    var_dump(error_get_last());
  }
}

function cmp($a, $b)
{
    if ($a->score == $b->score) {
        return 0;
    }
    return ($a->score < $b->score) ? 1 : -1;
}

function get_scores_content(){
  $fileContent = file_get_contents("scores.json");
  return json_decode($fileContent, false);
}

function write_table(){
    write_table_head();
      $objArray = get_scores_content();
        usort($objArray, "cmp");
        foreach ($objArray as $object){
          write_table_entry($object->name, $object->score);
        }
    write_table_end();
}

function write_table_head(){
  // begin table:
  echo '<table class="hs-table"><tr><th>name</th><th>score</th></tr>';
}

function write_table_end(){
// end table:
echo '</table>';
}

function write_table_entry($cur_name,$cur_score){
  echo '<tr><td>' . $cur_name . '</td><td>' . $cur_score . '</td></tr>';
}

?>
