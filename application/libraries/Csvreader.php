<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class csvreader {

    var $fields;            /** columns names retrieved after parsing */ 
    var $separator  =   ';';    /** separator used to explode each line */
    var $enclosure  =   '"';    /** enclosure used to decorate each field */

    var $max_row_size   =   50000000;    /** maximum row size to be used for decoding */

    function parse_file($p_Filepath) 
    {
        $file           =   fopen($p_Filepath, 'r');
        $this->fields   =   fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
        $keys_values        =   explode(',',$this->fields[0]);

        $content            =   array();
		
        $keys           =   $this->escape_string($keys_values);

        $i  =   1;
		$c = 1;
		$not = 0;
        while(($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false ) 
        {
           
			if( $row != null ) { // skip empty lines
                //$values         =   explode(',',$this->escape_string2($row[0]));
				$values         =   explode(',',$row[0]);
				if(count($keys) != count($values)){
				echo '<pre>';
				print_r($values);
				$not++;
				}				
                if(count($keys) == count($values)){
                    $arr            =   array();
                    $new_values =   array();
                    $new_values =   $this->escape_string($values);
					//print_r($new_values);
                    for($j=0;$j<count($keys);$j++){
                        if($keys[$j]    !=  ""){
                            $str = str_replace('^^@^^', ',',$new_values[$j]);
							$str = str_replace('^^#^^', ';',$str);
							$arr[$keys[$j]] =  $str;
                        }
                    }
                    $content[$i]    =   $arr;
                    $i++;
                }
            }
        }
        fclose($file);
		if($not) {
		die('stop here');
		}
        return $content;
    }
	
	function escape_string2($data)
    {
        return str_replace(',', '^^',$data);
    }

    function escape_string($data)
    {
        
		$result =   array();
        foreach($data as $row){
            $str = str_replace('"', '',stripslashes($row));
			//$str = str_replace(',', '^^@^^',stripslashes($str));
			$result[]   =  $str; 
        }
        return $result;
    }   
}
?>