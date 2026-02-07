<?php

use Carbon\Carbon;

  function dmY($date)
  {
	  $formattedDate = Carbon::parse($date);
	  return $formattedDate->format('d F Y');
  }
function changeDateFormat($date,$date_format){

	 
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);    
 
}

function dateformatman($date){

	 


	return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');    	

}

function dateformatmonth($date){

	 


	return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('M-Y');    	

}


function dateformatpc($date){

	 
 


    	return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d'); 
   
 
}

function dateformatman2($date){

	 


	return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d M Y');    	

}

function dateformatreport($date){

    return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d M y');

}


function dateformatman22($date){

    return ($date==null) ? null :  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d M y - H:i');

}

// $check =  check_role($_SESSION['campus_id'],$_SESSION['user_role_id'],'Department','Create');
// if($check!='Y') {
// $url= $_SERVER['HTTP_REFERER']; 
// echo "Access Denied";
// exit();
// }


function getInitials($name) {
    $words = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach ($words as $word) {
        if ($word !== '') {
            $initials .= strtoupper($word[0]);
        }
    }
    return $initials;
}



function check_role($UserID,$TableName,$Action)
{
		// action is (list,view,updat,delete etc)
		$check = DB::table('user_role')->where('UserID',$UserID)
 										->where('Table',$TableName)
										->where('Action',$Action)
 										->get();

		 
			 return $check;
		 								
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}



function productImagePath($image_name)
{
    return public_path('images/products/'.$image_name);
}

function test()
{
	return 'hello';
}

function yarr() {
  return 'yarr';
}


function getQuery($sql){
        $query = str_replace(array('?'), array('\'%s\''), $sql->toSql());
        $query = vsprintf($query, $sql->getBindings());     
        return $query;
}



function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = '  ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninty',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function agent($client,$amount,$basic)
{

	if(($client==1) &&($client>0))
	{
		$comission1 = 100;
	}
	elseif ($client==2) {
		$comission1 = 200;	
	}
	elseif ($client==3) {
		$comission1 = 400;		
	}
	elseif ($client==4) {
		$comission1 = 500;	
	}
	elseif ($client==5) {
		$comission1 = 700;	
	}
	elseif ($client==6) {
		$comission1 = 900;	
	}
	elseif ($client==7) {
		$comission1 = 1100;	
	}
	elseif ($client==8) {
		$comission1 = 1300;	
	}
	elseif ($client==9) {
		$comission1 = 1500;	
	}
	elseif ($client==10) {
		$comission1 = 1800;	
	}
	elseif ($client>=11) {
		$comission1=(12*200)-200;
	}
	else{
		
		$comission1=0;
	}



	if(($client>=3) &&($client<=4))
	{
		$comission2=200;
	}
	elseif (($client>=5) &&($client<=6)) {
		$comission2=300;
	}
	elseif (($client>=7) &&($client<=8)) {
		$comission2=400;
	}
	elseif (($client>=9) &&($client<=10)) {
		$comission2=600;
	}
	elseif ($client>=11)  {
		$comission2=1000;
	}
	else{
		
		$comission2=0;
	}

	if($amount<=10000)
	{
		$comission3=$amount*0.02;
	}
	elseif ($amount>=10001) {
		$comission3=$amount*0.03;	
	}
 		
 		$data = array (

 			'comission1' => $comission1,
 			'comission2' => $comission2,
 			'comission3' => $comission3 * 3.6731,
 			'grand'=>($comission1+$comission2+($comission3*3.6731)+$basic),
 			);

	// $total = ($comission1+$comission2+($comission3*3.6731));
	return ($data);




}


function closeri($client,$amount,$basic)
{

	$comission1=0;	
	$comission2=0;	
	$comission3=0;	
	$comission4=0;	
	if(($client<=5) &&($client>=3))
	{
		$comission1=0.03 * $amount;
	}
	elseif (($client<=9) &&($client>=6)) {
		$comission1=0.05 * $amount;
	}
	elseif (($client<=12) &&($client>=10)) {
		$comission1=0.06 * $amount;
	}
	elseif (($client<=15) &&($client>=13)) {
		$comission1=0.07 * $amount;
	}
	elseif (($client<=19) &&($client>=16)) {
		$comission1=0.08 * $amount;
	}
	elseif ($client>=20)  {
		$comission1=0.1 * $amount;
	}
	

	if(($client<=5) &&($client>=3))
	{
		$comission2=10 * $client;
	}
	elseif (($client<=9) &&($client>=6)) {
		$comission2=13 * $client;
	}
	elseif (($client<=12) &&($client>=10)) {
		$comission2=15 * $client;
	}
	elseif (($client<=15) &&($client>=13)) {
		$comission2=20 * $client;
	}
	elseif (($client<=19) &&($client>=16)) {
		$comission2=30 * $client;
	}
	elseif ($client>=20)  {
		$comission2=35 * $client;
	}
	
	 

	if(($client>=6) &&($client<=7))
	{
		$comission3=50;
	}
	elseif (($client>=8) &&($client<=9)) {
		$comission3=60;
	}
	elseif (($client>=10) &&($client<=12)) {
		$comission3=75;
	}
	elseif (($client>=13) &&($client<=14)) {
		$comission3=125;
	}
	elseif (($client>=15) &&($client<=17)) {
		$comission3=150;
	}
	elseif (($client>=18) &&($client<=19)) {
		$comission3=200;
	}
	elseif ($client>=20)  {
		$comission3=250;
	}
	



	if(($amount>=5000) &&($amount<=7499))
	{
		$comission4=75;
	}
	elseif (($amount>=7500) &&($amount<=9999)) {
		$comission4=150;
	 }
	elseif ($amount>10000)  {
		$comission4=200;
	}

 

		$data = array (

 			'comission1' => $comission1 ,
 			'comission2' => $comission2 ,
 			'comission3' => $comission2 ,
 			'comission4' => $comission4 ,
 			'grand' =>  ((($comission1+$comission2+$comission3+$comission4) * 3.67 )+ $basic)
 			);

 
	// $total = ($comission1+$comission2+($comission3*3.6731));
	return ($data);



}

function noel($client,$amount,$basic)
{

	$comission1=0;
	$comission2=0;
		if(($client>=0) &&($client<=9))
	{
		$comission1=0.03 * $amount;
	}
	elseif (($client>=10) &&($client<=19)) {
		$comission1=0.05 * $amount;
	}
	elseif (($client>=20) &&($client<=29)) {
		$comission1=0.06 * $amount;
	}
	elseif (($client>=30) &&($client<=39)) {
		$comission1=0.07 * $amount;
	}
	elseif (($client>=40) &&($client<=49)) {
		$comission1=0.08 * $amount;
	}
	elseif ($client>=50)  {
		$comission1=0.1 * $amount;
	}
	


	if($client<=10) 
	{
		$comission2=300;
	}
	elseif (($client>=15) &&($client<=24)) {
		$comission2=500;
	}
	elseif (($client>=25) &&($client<=29)) {
		$comission2=1000;
	}
	elseif (($client>=30) &&($client<=34)) {
		$comission2=1300;
	}
	elseif (($client>=35) &&($client<=44)) {
		$comission2=1500;
	}
	elseif (($client>=45) &&($client<=49)) {
		$comission2=2000;
	}
	elseif (($client>=50) &&($client<=55)) {
		$comission2=2500;
	}
	elseif (($client>=56) &&($client<=60)) {
		$comission2=3500;
	}
	elseif (($client>=61) &&($client<=65)) {
		$comission2=4000;
	}
	elseif (($client>=66) &&($client<=71)) {
		$comission2=5000;
	}
	elseif (($client>=72) &&($client<=80)) {
		$comission2=6000;
	}
	elseif (($client>=81) &&($client<=71)) {
		$comission2=7000;
	}
	elseif (($client>=92) &&($client<=100)) {
		$comission2=10000;
	}

	$comission2 = $comission2*0.272253;
	$tot= ($comission1+$comission2)+817;
 
	 
	$USD=$tot;
	$EUR = $tot*0.84137;
	$RON = $tot*4.40669;
	$AED = $tot*3.6732;
	$grand = $AED;

	
	 
	$data = array (

 			'comission1' => round( $comission1,2) ,
 			'comission2' => round($comission2,2) ,
 			'USD' => round($USD) ,
 			'EUR' => round($EUR),
 			'RON' => round($RON) ,
 			'AED' => round($AED) ,
  			'grand' =>  round($grand)
 			);

	 
return ($data);
}


function eu($no,$sum,$netdeposit,$ftd,$per,$total)
{


$comission1=0;
	if($no<=5 && $no>=0)
	{
		$comission1=$sum*0.03;
	}
	elseif ($no>=6 && $no <=10) {
		
		$comission1=$sum*0.05;

	}
	elseif ($no>=11 && $no <=15) {
		
		$comission1=$sum*0.06;

	}
	elseif ($no>=16 && $no <=20) {
		
		$comission1=$sum*0.07;

	}
	elseif ($no>=21 && $no <=25) {
		
		$comission1=$sum*0.08;

	}
	elseif ($no>=26 ) {
		
		$comission1=$sum*0.1;

	}

/////////////////////
	if($no<=3 && $no>=2)
	{
		$comission2=100*0.272253;
	}
	elseif ($no==4) {
		
		$comission2=300*0.272253;

	}
	elseif ($no==5) {
		
		$comission2=500*0.272253;

	}
	elseif ($no>=6 && $no <=7) {
		
		$comission2=700*0.272253;
	}
	elseif ($no>=8 && $no <=9) {
		
		$comission2=900*0.272253;

	}
	elseif ($no>=10 && $no <=11) {
		
		$comission2=1100*0.272253;

	}
	elseif ($no>=12 ) {
		
		$comission2=1300*0.272253;

	}


////////////////

	if($netdeposit<=25000 && $no>=10)
	{
		$comission3=$netdeposit*0.005;

	}
	elseif ($netdeposit>25000 && $netdeposit<50000 && $no>=10) {
		$comission3=$netdeposit*0.0075;
	}
	elseif ($netdeposit>=50000 && $netdeposit<100000 && $no>=10) {
		$comission3=$netdeposit*0.01;
	}
	elseif ($netdeposit>=100000 && $netdeposit<150000 && $no>=10) {
		$comission3=$netdeposit*0.0125;
	}
	elseif ($netdeposit>=150000 && $netdeposit<250000 && $no>=10) {
		$comission3=$netdeposit*0.015;
	}
	elseif ($netdeposit>=250000 && $no>=10) {
		$comission3=$netdeposit*0.02;
	}



	$tot= ($comission1+$comission2)+$comission3;
 
	 
	$USD=$tot+3089;
	$EUR = $USD*0.82825;
	$RON = $USD*4.0822;
	$AED = $USD*3.6703;
	$grand = $AED-3000;


$data = array (

 			'comission1' => round( $comission1,2) ,
 			'comission2' => round($comission2,2) ,
 			'comission3' => round($comission3,2) ,
 			'USD' => round($USD) ,
 			'EUR' => round($EUR),
 			'RON' => round($RON) ,
 			'AED' => round($AED) ,
  			'grand' =>  round($grand)
 			);

	 
return ($data);

  



}


function supervisor_name($SupervisorID)
{	
 	if($SupervisorID>0)
 	{
	$supervisor_name = DB::table('employee')->where('EmployeeID',$SupervisorID)->get();
	return ($supervisor_name[0]->FirstName .' '. $supervisor_name[0]->MiddleName .' '. $supervisor_name[0]->LastName);
	}
	else
	{
		return ('No Supervisor');
	}

}



