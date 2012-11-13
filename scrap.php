<?php
ini_set("max_execution_time", 0); 
set_time_limit(0);

if(isset($_GET['q']))
    $query=$_GET['q'];




$options = array(
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
            CURLOPT_HEADER         => false, 
            CURLOPT_TIMEOUT        => 120,
        );




$serp=array();

/*Google scraping datas ************************************************************************/
 $url = 'http://www.google.com/search?hl=en&q=' . urlencode($query) . '&num=10';
 $ch = curl_init($url);
 curl_setopt_array($ch,$options);
 $scraped="";
 $scraped.=curl_exec($ch);
 curl_close( $ch );
 $results = array();
 
 

 preg_match_all('/a href="([^"]+)" class=l.+?>(.+?)<\/a>.+?<span class="st">(.+?)<\/span>/',$scraped,$results);
 
 for ($i = 0; $i < count($results[1]); $i++) {
       
    $results[1][$i] = urldecode($results[1][$i]);
    $results[2][$i] = strip_tags($results[2][$i]);
    
   
    }
 

 
 addToSerp($results, $serp, "google");          //insert extracted data to storing structure
 /************************************************************************************/
 
 
 
    
    
 /* Yahoo scraping datas*************************************************************************************/  
 $url = 'http://search.yahoo.com/search?p='.urlencode($query);
 $ch = curl_init($url);
 curl_setopt_array($ch,$options);
 $scraped="";
 $scraped.=curl_exec($ch);
 curl_close( $ch );
 $results = array();
 
 preg_match_all('/a .+? class="yschttl spt" href="([^"]+)".+?>(.+?)<\/a>.+?<div class="abstr">(.+?)<\/div>/',$scraped,$results);
 
    
 for ($i = 0; $i < count($results[1]); $i++) {
       
    $results[1][$i] = urldecode($results[1][$i]);
    $results[2][$i] = strip_tags($results[2][$i]);
   
       
    preg_match_all('/\*\*(http(s)?:\/\/.*$)/', $results[1][$i], $urls);        // extract actual url
    $results[1][$i] = $urls[1][0];
    }

 
   
  
 addToSerp($results, $serp, "yahoo");                 //insert extracted data to storing structure
/************************************************************************************/
    
    

    

  
/*Bing scraping datas********************************************************************************/
    
 $url = 'http://www.bing.com/search?q='.urlencode($query);
 $ch = curl_init($url);
 curl_setopt_array($ch,$options);
 $scraped="";
 $scraped.=curl_exec($ch);
 curl_close( $ch );
 $results = array();
 
  preg_match_all('/<div class="sb_tlst"><h3><a href="([^"]+)".+?>(.+?)<\/a><\/h3>.+?<p>(.+?)<\/p>/',$scraped,$results);
 
 for ($i = 0; $i < count($results[2]); $i++) {
      $results[1][$i] = urldecode($results[1][$i]);  
      $results[2][$i] = strip_tags($results[2][$i]);
      
    }
 

 
   
  addToSerp($results, $serp, "bing");                                   //insert extracted data to storing structure
 /************************************************************************************/ 
  
  
  
  $serp2 = $serp;         //working structure in order to preserve the original (during the score calculation we need to modify it)
  
  
  /* Here begin the weighing of the results in the 3 engines and then the calculation of global ranking scores*/
  foreach ($serp2["google"] as $gRecord){
      $score = 11 - $gRecord["rank"];
      $bestRecord = $gRecord;
      $bestRecord["engine"] = "google";
      foreach ($serp2["yahoo"] as $index => $yRecord){
          if (strcmp($gRecord["url"], $yRecord["url"]) == 0){
              $score += 11 - $yRecord["rank"];
              $removedRecord = array_splice($serp2["yahoo"], $index, 1);
              if($removedRecord[0]["rank"] < $bestRecord["rank"]){
                $bestRecord = $removedRecord[0];
                $bestRecord["engine"] = "yahoo";
              }
              foreach($serp2["bing"] as $index => $bRecord){
                  if (strcmp($gRecord["url"], $bRecord["url"]) == 0){
                      $score += 11 - $yRecord["rank"];
                      $removedRecord = array_splice($serp2["bing"], $index, 1);
                      if($removedRecord[0]["rank"] < $bestRecord["rank"]){
                         $bestRecord = $removedRecord[0];
                         $bestRecord["engine"] = "bing";
                      }
                  }
              }
          }
      }
      $weightedScore = round($score / 3, 3);
      $bestRecord["score"] = $weightedScore;
      $serp["global"][] = $bestRecord;
  }
  
  foreach ($serp2["yahoo"] as $index => $yRecord){
         $score = 11 - $yRecord["rank"];
         $bestRecord = $yRecord;
         $bestRecord["engine"] = "yahoo";
         foreach($serp2["bing"] as $index => $bRecord){
              if (strcmp($yRecord["url"], $bRecord["url"]) == 0){
                  $score += 11 - $bRecord["rank"];
                  $removedRecord = array_splice($serp2["bing"], $index, 1);
                  if($removedRecord[0]["rank"] < $bestRecord["rank"]){
                     $bestRecord = $removedRecord[0];
                     $bestRecord["engine"] = "bing";
                  }
              }
          }
  
    $weightedScore = round($score / 3, 3);
    $bestRecord["score"] = $weightedScore;
    $serp["global"][] = $bestRecord;          
  }
  
  
  foreach($serp2["bing"] as $index => $bRecord){
      $score = 11 - $bRecord["rank"];
      $weightedScore = round($score / 3, 3);
      $bRecord["score"] = $weightedScore;
      $bRecord["engine"] = "bing";
      $serp["global"][] = $bRecord;
  }
  /**************************************************************************************************/    
  
  usort($serp["global"], 'sortByScore');          //global ranking ordering according to scores
  storeDataToDb($serp, $query);                   //store datas to DB
 
  
  
  
  
  
 
  function sortByScore($a, $b){
      if ($a["score"] > $b["score"]){
          return -1;
      }
      else if ($a["score"] < $b["score"]){
          return 1;
      }
      else{
          return 0;
      }        
  }
  
     
  
  function print_r_html ($arr) {
       echo "<pre>";
        print_r($arr);
        echo "</pre>";
} 
    
    
  function addToSerp($results,&$serp,$engine) {
     $i=0;
     foreach ($results[1] as $url){
     $serp["$engine"][]=array("url" => $url, "descr" => $results[2][$i], "abstr" => $results[3][$i], "rank" => $i + 1 );
     $i++;
     } 
  }
  
  function storeDataToDb($serp,$wordQuery){
          include "conn.php";
          $query = "INSERT into query (query) VALUES ('$wordQuery')";
          mysqli_query($conn,$query);
          $queryWordId = mysqli_insert_id($conn);
      
          
          foreach($serp as $engineName => $records){
              foreach($records as $record){
                  if ($engineName == "global"){
                      $url= mysqli_real_escape_string($conn, $record["url"]);
                      $abstr = mysqli_real_escape_string($conn, $record["abstr"]);
                      $descr = mysqli_real_escape_string($conn, $record["descr"]);
                      $score = $record["score"];
                      $rank = $record["rank"];
                      $sourceEngine = $record["engine"];
                      $query = "INSERT into $engineName (descr, url, abstract, rank, score, source_engine, query_id) VALUES ('$descr','$url', '$abstr', $rank, $score, '$sourceEngine', $queryWordId)";
                      mysqli_query($conn,$query);
                  }
                  else{
                      $url = mysqli_real_escape_string($conn, $record["url"]);
                      $abstr = mysqli_real_escape_string($conn, $record["abstr"]);
                      $descr = mysqli_real_escape_string($conn, $record["descr"]);
                      $rank = $record["rank"];
                      $query = "INSERT into $engineName (descr, url, abstract, rank, query_id) VALUES ('$descr','$url', '$abstr', $rank, $queryWordId)";
                      mysqli_query($conn,$query);
                  }
              }
          }
      
  }
  
  
  
  header('Content-Type:text/json');
  echo json_encode($serp);
?>
