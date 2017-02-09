<?php
    
    $sStyleSheetName = $_REQUEST['style'];
    $sXMLName = $_REQUEST['xmlname'];
    $sCatergories = $_REQUEST['catergories'];
    
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
    
    $sXSL = str_replace('$catergories', "'$sCatergories'", $sXSL);
    $sXSL = str_replace('$from', $_REQUEST['from'], $sXSL);
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
    
        // We'll be outputting a PDF
        header('Content-type: text/html');
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
    
