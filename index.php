<?php

  require __DIR__ . '/vendor/autoload.php';
  //require_once __DIR__ . '/vendor/twig/twig/lib/Twig/Autoloader.php';
  //use twig/Autoloader;

  //Twig_Autoloader::register();

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  //$loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array(
  //    'cache' => __DIR__ . '/compilation_cache',
  ));

  $iYear = date("Y");
  $iFrom = isset($_REQUEST['from'])?$_REQUEST['from']:$iYear - 20;
  $iReferences = isset($_REQUEST['references'])?$_REQUEST['references']:0;

  $iDetails = isset($_REQUEST['details'])?$_REQUEST['details']: $iYear - 15;

  if(isset($_REQUEST['job']))
  {
    if (filter_var($_REQUEST['job'], FILTER_VALIDATE_URL)) {

      $handle = fopen($_REQUEST['job'], "r");
      $contents = fread($handle, filesize($_REQUEST['job']));
      $sJob = strip_tags($contents);
    } else {
      $sJob = $_REQUEST['job'];
    }

  }
  else
  {
    $sJob = file_get_contents('job.txt');
  }

  if(isset($_REQUEST['xmlname']))
  {
   $sXMLName = $_REQUEST['xmlname'];
  }
  else
  {
    $sXMLName = 'CurrentCV';
  }

  $sLayout = isset($_REQUEST['layout'])?$_REQUEST['layout']:null;
  $sStyle = isset($_REQUEST['style'])?$_REQUEST['style']:null;

  $bPreview = isset($_REQUEST['preview']);

  if(isset($_REQUEST['format']) && $_REQUEST['format'] == "PDF"){
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
    //'caption' => "Categories =" .var_export(array_keys($aCategories), TRUE)
    'caption' => "Skills in CV : " .join(array_keys($aCategories),", ")
  );
  //echo "\n<br><pre>\naCategories =" .var_export(array_keys($aCategories), TRUE)."</pre>";

  //preg_match_all('/'.join('|', $aCategories).'/i', $sJob, $aMatches);
  //echo "\n<br><pre>\naMatches =" .var_export($aMatches, TRUE)."</pre>";
  //$sThesaurusKey = 'e4aeec2b38f65bf0c0ab184bb0a3fe14';

  $ch = curl_init("https://api.algorithmia.com/v1/algo/StanfordNLP/PartofspeechTagger/0.1.0");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-type: application/text;charset="utf-8"',
    'Authorization: Simple simyGavBCByypprWgpDOxe7OQAB1',
    //'metadata.content_type: text'
  ));
  //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: Simple simyGavBCByypprWgpDOxe7OQAB1'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(str_replace(array("\n", "\t"), " ", $sJob))));
  if( ! $sReturn = curl_exec($ch))
  {
      trigger_error(curl_error($ch));
  }
  curl_close($ch);
  //echo("<pre>sReturn =".$sReturn."</pre>");

  $aReturn = json_decode($sReturn, true);
  //echo("<pre>aReturn =".var_export($aReturn)."</pre>");
  $sResult = $aReturn['result'];
  //echo("<pre>sResult =".$sResult."</pre>");
  $aResult =  explode ( " " , $sResult);

  //echo("<pre>sValue =\n");
  //echo("<pre>aResult =".var_export($aResult)."</pre>");
  $aWords = [];
  foreach ($aResult as &$sValue) {
    //echo("<pre>sValue =".$sValue."</pre>");
    $aLine = explode("_", $sValue);
    if(strlen($aLine[0]) > 3 ){
      //echo ($aLine[0]." ".$aLine[1]."\n");
      if( $aLine[1] == "VBP" || $aLine[1] == "JJ" || $aLine[1] == "NN"){
        $aWords[] = preg_replace('/[0-9]/','',$aLine[0]);
      }
    }
  }
  echo("</pre>");

  $aWords = array_unique ( $aWords);

  $aMessages[] = array(
    'type'  => 'warning',
    //'caption' => "Categories =" .var_export(array_keys($aCategories), TRUE)
    'caption' => "Keywords in Job : " .join($aWords, ", ")
  );

  /*
  curl -X POST -d '<INPUT>' -H 'Content-Type: application/json' -H 'Authorization: Simple simyGavBCByypprWgpDOxe7OQAB1' https://api.algorithmia.com/v1/algo/StanfordNLP/PartofspeechTagger/0.1.0
  */

  $oAltWords = new alternativeWords();

  $aMatches = array();
  foreach($aCategories as $sCategory)
  {
    $aCategory = $oAltWords->getAltWords($sCategory);
    //echo "\n<br><pre>\naCategory  =" .var_export($aCategory , TRUE)."</pre>";
    if($aCategory){
      foreach($aCategory as $sCat)
      {
        if(stripos($sJob, $sCat) !== false)
        {
            $aMatches[$sCategory] = $sCategory;
        }
      }
    }
  }

  $aMessages[] = array(
    'type'  => 'warning',
    //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
    'caption' => "Skills Matched in Job  :" .join(array_keys($aMatches),", ")
  );

  //echo "\n<br><pre>\n aMatches  =" .var_export($aMatches , TRUE)."</pre>";

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
    'sStyle'    => $sStyle,
    "sXMLName"  => $sXMLName,
    "bPreview"  => $bPreview,
    "iReferences"=> $iReferences
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
    return isset($this->aAltWords[$sWord])?$this->aAltWords[$sWord]:[];
  }

  function fetchNewWords($sWord)
  {
    if(substr_count($sWord, " ") == 0){
      try {
        $sThes = file_get_contents('http://words.bighugelabs.com/api/2/1c14925fc13e4b05b0af1072ce7b53c0/'.urlencode($sWord).'/php');
        if($sThes === false){
          $aMessages[] = array(
            'type'  => 'warning',
            //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
            'caption' => "Can't find word '".$sWord."'"
          );

        } else {

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
      } catch (Exception $e) {
        $aMessages[] = array(
            'type'  => 'Error',
            //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
            'caption' => "Can't find word '".$sWord."'"
          );
      }
    }
  }
}

?>
