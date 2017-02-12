<?php
    
    $sLayout = $_REQUEST['layout'];
    $sStyle = $_REQUEST['style'];
    $sXMLName = $_REQUEST['xmlname'];
    $sCatergories = $_REQUEST['catergories'];
    $sFormat = $_REQUEST['format'];
    $iFrom = $_REQUEST['from'];
    $iDetails = $_REQUEST['details'];
    
    //echo $sCatergories;
    
    if(!($sXMLName))
    {
      echo "'xmlname' or 'style' not set";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    $xslDoc = new DOMDocument();
    $sLayoutSheetFN = 'stylesheet/'.$sLayout.'.xsl';
    $sStyleSheetFN = 'stylesheet/'.$sStyle.'.css';

    $sXSL = '';
    if(file_exists($sLayoutSheetFN)){
      $sXSL = file_get_contents($sLayoutSheetFN);
    }
    
    if(!($sXSL))
    {
      echo "XSL not found";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    $aCats = explode(",", $sCatergories);
    $sCats = "'".implode("|", $aCats)."'";
    
    $sXSL = str_replace('$catergories', $sCats, $sXSL);
    $sXSL = str_replace('$from', $iFrom, $sXSL);
    $sXSL = str_replace('$details', $iDetails, $sXSL);
    
    $xslDoc->loadXML($sXSL);
    
    $xmlDoc = new DOMDocument();
    $bResult = $xmlDoc->load('xml/'.$sXMLName.'.xml');
    if(!($bResult))
    {
      echo "sXML not set";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
        
    $node = $xmlDoc->createElement("params");
    foreach ($aCats as $sCat){
       $catnode = $xmlDoc->createElement("category", $sCat);
       $node->appendChild($catnode);
    }
    $xmlDoc->appendChild($node);
    
    $proc = new XSLTProcessor();
    $proc->registerPHPFunctions();
    $proc->importStylesheet($xslDoc);
    
    
    $outDoc = $proc->transformToDoc($xmlDoc);
    $sOutFile = $sLayout.'.html';
    $outDoc->save('output/'.$sOutFile);
    
    if(strtolower($sFormat) == 'pdf'){
      $sHtmlFile = 'output/'.$sOutFile;
      $sOutFile = $sLayout.".pdf";
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

    if(file_exists('output/'.$sOutFile)){
      // The PDF source is in original.pdf
      readfile('output/'.$sOutFile);
    } else {
      echo ('output/'.$sOutFile)." not found";
      header('HTTP/1.1 500 Server Error', true, 404);
      exit;
    }
    
