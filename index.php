<?php

  #require __DIR__ . '/vendor/autoload.php';
  require_once 'vendor/autoload.php';
  
  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new Twig_Environment($loader, array(
  //    'cache' => __DIR__ . '/compilation_cache',
  ));

  $iYear = 0+date("Y");
  $iFrom = (isset($_REQUEST['from'])&&$_REQUEST['from'])?(0+$_REQUEST['from']):($iYear - 20);
  $iDetails = (isset($_REQUEST['details'])&&$_REQUEST['details'])?(0+$_REQUEST['details']): ($iYear - 15);
  
  $iEducationFrom = (isset($_REQUEST['educationfrom'])&&$_REQUEST['educationfrom'])?(0+$_REQUEST['educationfrom']):3;
  $iEducationDetails = (isset($_REQUEST['educationdetails'])&&$_REQUEST['educationdetails'])?(0+$_REQUEST['educationdetails']): ($iYear - 20);
  
  $iReferences = isset($_REQUEST['references'])?(0+$_REQUEST['references']):0;
  $sType = isset($_REQUEST['type'])?($_REQUEST['type']):"";
  
  if(isset($_REQUEST['job']))
  {
    if (filter_var($_REQUEST['job'], FILTER_VALIDATE_URL)) {
      //print($_REQUEST['job']);
      $contents = file_get_contents($_REQUEST['job']);
      $oDoc = new DOMDocument();
      $bResult = $oDoc->loadHTML($contents);
      $oContent = $oDoc->getElementsByTagName('body')->item(0);
      $contents = $oDoc->saveHTML($oContent);
      //$handle = fopen($_REQUEST['job'], "r");
      //$contents = fread($handle, filesize($_REQUEST['job']));
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

  $input = str_replace(array("\n", "\t"), " ", $sJob);
  $client = Algorithmia::client("simyGavBCByypprWgpDOxe7OQAB1");
  #$algo = $client->algo("StanfordNLP/PartofspeechTagger/0.1.0");
  #$algo = $client->algo('nlp/Summarizer/0.1.3');
  $algo = $client->algo("ApacheOpenNLP/POSTagger/0.1.1");

  #$algo = $client->algo("demo/Hello/0.1.0");
  $algo->setOptions(["timeout" => 300]); //optional
  try{
  $sReturn = ($algo->pipe($input)->result);
  } catch(Exception $err){
    $sReturn ="";
    $aMessages[] = array(
      'type'  => 'error',
      //'caption' => "Categories =" .var_export(array_keys($aCategories), TRUE)
      'caption' => $err->getMessage()
    );
  
  }

  /*
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
  */

  $aReturn = json_decode($sReturn, true);
  echo("<pre>aReturn =".var_export($aReturn)."</pre>");
  $sResult = $aReturn['result'];
  //echo("<pre>sResult =".$sResult."</pre>");
  $aResult =  explode ( " " , $sResult);

  echo("<pre>sValue =\n");
  echo("<pre>aResult =".var_export($aResult)."</pre>");
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

  $aMessages[] = array(
    'type'  => 'warning',
    //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
    'caption' => "Using Layout: $sLayout, Style: $sStyle, XML Name: $sXMLName & Type: $sType"
  );


  $context = array(
    'aMatches'  => $aMatches,
    'sJob'      => $sJob,
    'iFrom'     => $iFrom,
    'iDetails'  => $iDetails,
    'iEducationFrom'     => $iEducationFrom,
    'iEducationDetails'  => $iEducationDetails,

    'aMessages' => $aMessages,
    'aLayouts'  => $aLayouts,
    'sLayout'   => $sLayout,
    'aStyles'   => $aStyles,
    'sStyle'    => $sStyle,
    "sXMLName"  => $sXMLName,
    "sType" => $sType,
    "bPreview"  => $bPreview,
    "iReferences"=> $iReferences
  );

  //print_r($context);

  $template = $twig->load('index.html');
  $template->display($context);

class alternativeWords
{
  var $sAltWordsFile = 'altwords.dat';
  var $sAltWordsFileJSON = 'altwords.json';
  
  var $aAltWords = array();
  function __construct()
  {
    $aAltWords = array();
    #include($this->sAltWordsFile);

    $sWords = file_get_contents($this->sAltWordsFileJSON);
    $this->aAltWords = json_decode($sWords, true);

    $this->save();
  }

  function save()
  {
    file_put_contents($this->sAltWordsFileJSON, json_encode($this->aAltWords,JSON_PRETTY_PRINT));
    #$sStr = "<?php\n \$aAltWords = ".var_export($this->aAltWords, true).";\n";
    #file_put_contents($this->sAltWordsFile, $sStr);
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
    $sWord = trim($sWord);
    echo "fetchNewWords($sWord)";
    if(substr_count($sWord, " ") == 0){
      try {
        $sThesaurus = file_get_contents('http://words.bighugelabs.com/api/2/1c14925fc13e4b05b0af1072ce7b53c0/'.urlencode($sWord).'/php');
        if($sThesaurus === false){
          $this->aAltWords[$sWord][] = $sWord;
          $this->save();
          $aMessages[] = array(
            'type'  => 'warning',
            //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
            'caption' => "Can't find word '".$sWord."'"
          );
          
        } else {

          //echo "\n<br><pre>\nsThesaurus  =" .$sThesaurus ."</pre>";
          $aThesaurus = unserialize($sThesaurus);
          //echo "\n<br><pre>\naThesaurus  =" .var_export($aThesaurus , TRUE)."</pre>";

          if (isset($aThesaurus['verb']['syn']))
          {
            $this->aAltWords[$sWord] = $aThesaurus['verb']['syn'];
          }
          else
          {
              $this->aAltWords[$sWord] = $aThesaurus['noun']['syn'];
          }
          $this->aAltWords[$sWord][] = $sWord;
          $this->save();
        }
      } catch (Exception $e) {
          $this->aAltWords[$sWord][] = $sWord;
          $this->save();
          $aMessages[] = array(
            'type'  => 'Error',
            //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
            'caption' => "Can't find word '".$sWord."'"
          );
      }
    } else {
      $this->aAltWords[$sWord][] = $sWord;
      $this->save();
      $aMessages[] = array(
        'type'  => 'Warning',
        //'caption' => "From Job =" .var_export(array_keys($aMatches), TRUE)
        'caption' => "Added phrase '".$sWord."'"
      );
    }
  }
}

?>
