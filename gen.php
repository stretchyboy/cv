<?php
    
    $sStyleSheetName = $_REQUEST['style'];
    $sXMLName = $_REQUEST['xmlname'];
    $sCatergories = $_REQUEST['catergories'];
    
    if(!($sStyleSheetName && $sXMLName))
    {
      echo "sXSL not set";
      header('HTTP/1.1 404 Not Found', true, 404);
      exit;
    }
    
    $xslDoc = new DOMDocument();
    
    $sXSL = file_get_contents('xsl/'.$sStyleSheetName.'.xsl');
    if(!($sXSL))
    {
      echo "sXSL not set";
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
        $foDoc->save('fo/'.$sTempFile);
        $sOutFile = join('_', $aStyleParts).'.'.$sType;
        exec('/home/martyn/fop-0.95/fop '.'fo/'.$sTempFile.' '.'output/'.$sOutFile);
        
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
    
    // The PDF source is in original.pdf
    readfile('output/'.$sOutFile);
    
