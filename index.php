<?php

  if($_REQUEST['job']) 
  {
   $sJob = $_REQUEST['job'];
  }
  else
  {
    $sJob = file_get_contents('job.txt');
  }

  $sXMLName = $_REQUEST['xmlname'];
  $xmlDoc = new DOMDocument();
  $bResult = $xmlDoc->load('xml/'.$sXMLName.'.xml');
  if(!($bResult))
  {
    echo "sXML not set";
    header('HTTP/1.1 404 Not Found', true, 404);
    exit;
  }
  
  $oXPath = new DOMXPath($xmlDoc);
  $tbody = $xmlDoc->getElementsByTagName('cv')->item(0);

// our query is relative to the tbody node
$query = 'count(//category)';

$entries = $oXPath->evaluate($query, $tbody);


//echo "\n<br><pre>\nentries  =" .var_export($entries , TRUE)."</pre>";
  
  
  $entries = $oXPath->evaluate('//category', $xmlDoc);
  //echo "\n<br><pre>\nentries  =" .var_export($entries , TRUE)."</pre>";

  $aCategories = array();
  foreach ($entries as $domElement){
  $sCategory =  $domElement->firstChild->nodeValue;
  $aCategories[$sCategory] = $sCategory;
  
  
}
asort($aCategories);
//echo "\n<br><pre>\naCategories =" .var_export(array_keys($aCategories), TRUE)."</pre>";

preg_match_all('/'.join('|', $aCategories).'/i', $sJob, $aMatches);
//echo "\n<br><pre>\naMatches =" .var_export($aMatches, TRUE)."</pre>";
//$sThesaurusKey = 'e4aeec2b38f65bf0c0ab184bb0a3fe14';

  $oAltWords = new alternativeWords();
  
  
  $aMatches = array();
  foreach($aCategories as $sCategory)
  {
    $aCategory = $oAltWords->getAltWords($sCategory);
    //echo "\n<br><pre>\naCategory  =" .var_export($aCategory , TRUE)."</pre>";
    
    foreach($aCategory as $sCat)
    {
      if(stripos($sJob, $sCat) !== false)
      {
          $aMatches[$sCategory] = $sCategory;
      }
    }
  }

  //echo "\n<br><pre>\naMatches =" .var_export(array_values($aMatches), TRUE)."</pre>";

echo '<iframe width="100%" height="400px"  src="http://localhost/projects/cv/gen.php?style=html_CV_Grey&xmlname=cv&from=1998&catergories='.urlencode(join(',',$aMatches)).'"></iframe>';

echo '<iframe width="100%" height="40px"  src="http://localhost/projects/cv/gen.php?style=pdf_CV_Grey&xmlname=cv&from=1998&catergories='.urlencode(join(',',$aMatches)).'"></iframe>';


class alternativeWords
{
  var $sAltWordsFile = 'altwords.dat';
  var $aAltWords = array();
  function __construct()
  {
    $aAltWords = array();
    include($this->sAltWordsFile);
    
    //$sWords = file_get_contents($this->sAltWordsFile);
    
    //$this->aAltWords = unserialize($sWords);
    $this->aAltWords = $aAltWords;
  }
  
  function save()
  {
    //file_put_contents($this->sAltWordsFile, serialize($this->aAltWords));
    $sStr = "<?php\n \$aAltWords = ".var_export($this->aAltWords, true).";\n";
    file_put_contents($this->sAltWordsFile, $sStr);
  }
  
  function getAltWords($sWord)
  {
    $sWord = strtolower($sWord);
    
    if(!isset($this->aAltWords[$sWord]))
    {
      $this->fetchNewWords($sWord);
    }
    return $this->aAltWords[$sWord];   
  }
  
  function fetchNewWords($sWord)
  {
    $sThes = file_get_contents('http://words.bighugelabs.com/api/2/e4aeec2b38f65bf0c0ab184bb0a3fe14/'.$sWord.'/php');
    echo "\n<br><pre>\nsThes  =" .$sThes ."</pre>";
    $aThes = unserialize($sThes);
    echo "\n<br><pre>\naThes  =" .var_export($aThes , TRUE)."</pre>";
    
    if (isset($aThes['verb']['syn']))
    {
      $this->aAltWords[$sWord] = $aThes['verb']['syn'];
    }
    else
    {
        $this->aAltWords[$sWord] = $aThes['noun']['syn'];
    }
    $this->aAltWords[$sWord][] = $sWord;
    $this->save();
  }
}

?>
<html>
<head></head>
<body>


<form method="post">

<textarea name='job' cols="80" rows="20"><?php echo $sJob;?></textarea>
  
  
  <input type="submit" value="Go" />
</form>

</body>
</html>
