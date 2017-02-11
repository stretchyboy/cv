<?php
    
    $sStyleSheetName = $_REQUEST['style'];
    $sXMLName = $_REQUEST['xmlname'];
    $sCatergories = $_REQUEST['catergories'];
    
    $sFormat = $_REQUEST['format'];
    
    if(!($sStyleSheetName && $sXMLName))
    {
      echo "'xmlname' or 'style' not set";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    $xslDoc = new DOMDocument();
    $sStyleSheetFN = 'stylesheet/'.$sStyleSheetName.'.xsl';
    $sXSL = '';
    if(file_exists($sStyleSheetFN)){
      $sXSL = file_get_contents($sStyleSheetFN);
    }
    if(!($sXSL))
    {
      echo "XSL not found";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    function CatFormat($sCat)
    {
      return "'".$sCat."'";
    }
    
    $aCats = explode(",", $sCatergories);
    $sCats = "'".implode("|", $aCats)."'";
    
    //echo $sCats;
    
    $sXSL = str_replace('$catergories', $sCats, $sXSL);
    
    $iYear = date("Y");
    $iFrom = $_REQUEST['from'];
    if(!$iFrom){
      $iFrom = $iYear - 20;
    }
    
    $iDetails = $_REQUEST['details'];
    if(!$iDetails){
      $iDetails = $iYear - 15;
    }
    
    //echo "<pre>".$iFrom."   ".$iDetails."</pre>";
    $sXSL = str_replace('$from', $iFrom, $sXSL);
    $sXSL = str_replace('$details', $iDetails, $sXSL);
    
    //echo $sXSL;
    //exit;
    
    $xslDoc->loadXML($sXSL);
    
    $xmlDoc = new DOMDocument();
    $bResult = $xmlDoc->load('xml/'.$sXMLName.'.xml');
    if(!($bResult))
    {
      echo "sXML not set";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    $aStyleParts = split('_', $sStyleSheetName);
    $sType = array_shift($aStyleParts);
    
    
    $proc = new XSLTProcessor();
    $proc->registerPHPFunctions();
    $proc->importStylesheet($xslDoc);
    
    
    switch ($sType)
    {
      case 'pdf':
        
        $foDoc = $proc->transformToDoc($xmlDoc);
        $sTempFile = join('_', $aStyleParts).'.xml';
        // If fo doesn't exist create it
        $foDoc->save('fo/'.$sTempFile);
        $sOutFile = join('_', $aStyleParts).'.'.$sType;
        exec('/usr/bin/fop '.'fo/'.$sTempFile.' '.'output/'.$sOutFile);
        
        // We'll be outputting a PDF
        header('Content-type: application/pdf');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.$sOutFile.'"');
        break;
      case 'html':
        
        $outDoc = $proc->transformToDoc($xmlDoc);
        $sOutFile = join('_', $aStyleParts).'.'.$sType;
        $outDoc->save('output/'.$sOutFile);
        
        if($sFormat == 'pdf'){
          $sHtmlFile = 'output/'.$sOutFile;
          $sOutFile = $sOutFile.".pdf";
          require 'pdfcrowd.php';

          try
          {   
              // create an API client instance
              $client = new Pdfcrowd("stretchyboy", "42ffcd1ef142470fbb24e27bb20d4c23");
              $client->setPageWidth("210mm");
              $client->setPageHeight("297mm");
              $client->enableBackgrounds(true);
              $client->setAuthor("Martyn Eggleton");
              //$client->setInitialPdfExactZoom("100");
              $client->setNoModify(true);
              //$client->setFooterHtml('Page %p of %n');
              
              $sBase = "https://martyns-cv-stretchyboy.c9users.io/";
              $pdf = $client->convertURI($sBase.$sHtmlFile);
              //$pdf = $client->convertFile($sHtmlFile);

              // set HTTP response headers
              header("Content-Type: application/pdf");
              header("Cache-Control: max-age=0");
              header("Accept-Ranges: none");
              
              echo $pdf;
          }
          catch(PdfcrowdException $why)
          {
              echo "Pdfcrowd Error: " . $why;
          }
          
        } else {
          // We'll be outputting a PDF
          header('Content-type: text/html');
        }
        // It will be called downloaded.pdf
        break;
    }
    
    if(file_exists('output/'.$sOutFile)){
      // The PDF source is in original.pdf
      readfile('output/'.$sOutFile);
    } else {
      echo ('output/'.$sOutFile)." not found";
      header('HTTP/1.1 500 Server Error', true, 404);
      exit;
    }
    
