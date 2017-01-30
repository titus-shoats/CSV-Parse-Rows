<?php
/*****
Returns only Customer created between June 22nd, 2014 and July 22nd, 2014
Rows are then order in ascending order (by created_at) 
***/

/****Grab csv file**/
$csv = file('sample_data.csv');
$data= [];
foreach($csv as $file){

    /****Put rows/columns intro array**/

	$data[] = str_getcsv($file);
}

/****Create a date instance**/
$date = new DateTime();

/****Create array for date #1 - June****/
$date_array0 =[];

/****Create array for date #2 - June****/
$date_array1 =[];

/****Create match rows to match dates ***/
$match_rows =[];
$match_array=[];

/****Loop through all csv rows*******/
foreach ($data as $key => $value) {

	/****Push original values into array to be matched against later ****/

	array_push($match_rows, $value);
	if(is_array($value)){

		
		foreach ($value as $k => $v) {
			
            
        /****Get first row which is created_at timestamp****/

			if($k==1 ){
             
			/****If row does not include string created_at****/
			 if($v!=="created_at" ){
			
            
           /****Creates timestamp from row value****/ 
		   $date->setTimestamp($v);
           $date_filter = explode("-" ,  $date->format('m-d-Y h:i:s'));
			
			
			   /****Get year of 2014***/
			  if($date_filter[2] == 2014){
                   
                       /****Get month of June && July***/
                   if($date_filter[0] == 6 or $date_filter[0] ==7){

                             /****If row value is June and greater than 22nd date - push into date array #1****/
                   	     if( $date_filter[1] >= 22 && $date_filter[0] == 6 ){
                             array_push($date_array0, $date_filter[0] ."/" . $date_filter[1] . "/" . $date_filter[2]);
                   	     }
                             /****If row value is July and less than 22nd date - push into date array #2****/

                   	      if( $date_filter[1] <= 22 && $date_filter[0] == 7 ){
                             array_push($date_array1,$date_filter[0] ."/" . $date_filter[1] . "/" . $date_filter[2] );
                   	     }
        	    
                   }
			  }
         
			
		     }
		  }

		}
	}
}

/****Merge both date arrays****/
$all_dates = array_merge($date_array0,$date_array1);
 
/****Create timestamp var ****/ 
$timestamp = [];

/****Loop through all dates, and convert to timestamp****/
$orderByDate = array();
foreach ($all_dates as $key => $row) {
    $orderByDate[$key]  = strtotime($row);
}

/****Re orders date by ascending ****/
array_multisort($orderByDate, SORT_ASC);
foreach ($orderByDate as $k1 => $v1) {

	/****Set timestamp for dates ****/
    $date->setTimestamp($v1);
    
    /****Format dates, and pushes date into timestamp ****/
    $date_filter = explode("-" ,  $date->format('m-d-Y h:i:s'));

    array_push($timestamp,$date_filter[0] ."/" . $date_filter[1] . "/" . $date_filter[2] ); 

}

/**** Loop through orignal csv rows to match orginal dates against new dates converted timestamp date****/

foreach ($match_rows as $key => $value) {
	
	 if(is_array($value)){
	 	 


	 	
	 	 if($value[0] !== "id" or $value[1] !== "created_at" or $value[2] !== "first_name" or $value[3] !== "last_name" 
	 	 or $value[4] !== " email" or $value[5] !== "gender" or $value[6] !== "company"or $value[7] !== "currency" 
	 	 or $value[8] !== "word" or $value[9] !== "drug_brand" or $value[10] !== "drug_name" 
	 	 or $value[11] !== "drug_company"or $value[12] !== "pill_color" 
	 	  or $value[13] !== "frequency"or $value[14] !== "token"  or $value[15] !== "keywords"or $value[16] !== "bitcoin_address"){
	 	 	 
	 	 	  

             /**** Set timestamp for created_at column****/
 
	 	 	 $date->setTimestamp((int)$value[1]);

			 $date_filter = explode("-" ,  $date->format('m-d-Y h:i:s'));

               /**** Checks if original date matches converted timestamp****/
              if(in_array($date_filter[0] ."/" . $date_filter[1] . "/" . $date_filter[2], $timestamp)){
              

                /**** If date matches, push date into array***/
               array_push($match_array,$value);
              	

              }

             
	 	 }
	 }
}

/*****
Returns only Customer created between June 22nd, 2014 and July 22nd, 2014
Rows are then order in ascending order (by created_at) 
***/
$customers = array_map(function($item0,$item1){
return "<div style='font-size:17px;font-family:Arial'>" .
              "<strong style='color:black'>" .$item1 ." | "."</strong>"." ".$item0[0] ." ". $item0[1] ." 
	        ". $item0[2] ." ". $item0[3] ." ". $item0[4] ." ". $item0[5] ." ". $item0[6] ." ". $item0[7]
	        ."<strong style='color:red'>" ." ".$item0[8] ."  "."</strong>" 
	           ." ". $item0[9] ." ". $item0[10] ." ". $item0[11] ." ". $item0[12]  ." ". $item0[13] ." ". $item0[14] ." ". $item0[15] . $item0[16] . "\n" ." "
      . "</div>";


},$match_array,$timestamp);

echo implode(',',$customers);
  
?>