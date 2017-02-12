<?php

  require __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/vendor/twig/twig/lib/Twig/Autoloader.php';
  //use twig/Autoloader;
  
  Twig_Autoloader::register();
  
  $loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array(
  //    'cache' => __DIR__ . '/compilation_cache',
  ));
  
  $iYear = date("Y");
  $iFrom = $_REQUEST['from'];
  if(!$iFrom){
    $iFrom = $iYear - 20;
  }
  
  $iDetails = $_REQUEST['details'];
  if(!$iDetails){
    $iDetails = $iYear - 15;
  }
  
  if($_REQUEST['job']) 
  {
   $sJob = $_REQUEST['job'];
  }
  else
  {
    $sJob = file_get_contents('job.txt');
  }


  if($_REQUEST['xmlname']) 
  {
   $sXMLName = $_REQUEST['xmlname'];
  }
  else
  {
    $sXMLName = 'CurrentCV';
  }

  $sLayout = $_REQUEST['layout'];
  $sStyle = $_REQUEST['style'];

  $bPreview = false;
  if($_REQUEST['preview']){
    $bPreview = true;
  }
  
  
  if($_REQUEST['format'] == "PDF"){
    require("gen.php");
    exit;
  }
  
  
  $xmlDoc = new DOMDocument();
  $bResult = $xmlDoc->load('xml/'.$sXMLName.'.xml');
  if(!($bResult))
  {
    header('HTTP/1.1 404 Not Found', true, 404);
    echo "sXML not set";
    exit;
  }
  
  $oXPath = new DOMXPath($xmlDoc);
  $tbody = $xmlDoc->getElementsByTagName('cv')->item(0);

  // our query is relative to the tbody node
  $query = 'count(//category)';
  
  $entries = $oXPath->evaluate($query, $tbody);
  $entries = $oXPath->evaluate('//category', $xmlDoc);
  
  $aCategories = array();
  foreach ($entries as $domElement){
    $sCategory =  $domElement->firstChild->nodeValue;
    $aCategories[$sCategory] = $sCategory;
  }
    
  asort($aCategories);
  $aMessages[] = array(
    'type'  => 'warning', 
    'caption' => "Categories =" .var_export(array_keys($aCategories), TRUE)
  );
  //echo "\n<br><pre>\naCategories =" .var_export(array_keys($aCategories), TRUE)."</pre>";
  
  //preg_match_all('/'.join('|', $aCategories).'/i', $sJob, $aMatches);
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
  
  //echo "\n<br><pre>\n aMatches  =" .var_export($aMatches , TRUE)."</pre>";
    
  $aMessages = array(
    /*array(
      'type'  => 'warning', 
      'caption' => "Just testing"
    )*/
  );
  
  $aLayouts = array(
    array(
      'name'    => "html_plain", 
      'caption' => 'Plain'
    ),
    array(
      'name'    => "html_functional", 
      'caption' => 'Functional'
    )
  );
  
  $aStyles = array(
    array(
      'name'    => "plain", 
      'caption' => 'Plain'
    )
  );
  
  $template = $twig->load('index.html');
  $template->display(array(
    'aMatches'  => $aMatches,
    'sJob'      => $sJob,
    'iFrom'     => $iFrom,
    'iDetails'  => $iDetails,
    'aMessages' => $aMessages,
    'aLayouts'  => $aLayouts,
    'sLayout'   => $sLayout,
    'aStyles'   => $aStyles,
    'sStyle'    => $sStyles,
    "sXMLName"  => $sXMLName,
    "bPreview"  => $bPreview
  ));
  

class alternativeWords
{
  var $sAltWordsFile = 'altwords.dat';
  var $aAltWords = array();
  function __construct()
  {
    $aAltWords = array();
    include($this->sAltWordsFile);
    
    $sWords = file_get_contents($this->sAltWordsFile);
    
    $this->aAltWords = unserialize($sWords);
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
    $sThes = file_get_contents('http://words.bighugelabs.com/api/2/1c14925fc13e4b05b0af1072ce7b53c0/'.urlencode($sWord).'/php');
    if($sThes){
      //echo "\n<br><pre>\nsThes  =" .$sThes ."</pre>";
      $aThes = unserialize($sThes);
      //echo "\n<br><pre>\naThes  =" .var_export($aThes , TRUE)."</pre>";
      
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
}

?>