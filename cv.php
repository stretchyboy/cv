<?php
//http://localhost/projects/cv/gen.php?style=html_CV_Grey&xmlname=cv&catergories=Description,System%20Design,Linux,Hardware,Software,WebDev,System+Admin&from=1998

    function xsl_format_date($xVal, $sFormat)
    {

      echo "xVal =" .var_export($xVal, TRUE)."\n";
      echo "sFormat =" .$sFormat."\n";
    }

    $xslDoc = new DOMDocument();

//    $sXSL = file_get_contents('cv2fo.xsl');
    $sXSL = file_get_contents('stylesheet/pdf_CV_Grey.xsl');//cv.xsl');

    $sXSL = preg_replace('/format-date\(([^\,]+),([^\)]+)\)/', "php:function('xsl_format_date', $1, $2)", $sXSL);

    //$sXSL = str_replace('format-date', "php:function('xsl_format_date'," $sXSL);


    $sXSL = str_replace('$catergories', "'Description,System Design,Linux,Hardware,Software,WebDev'", $sXSL);


    //echo "sXSL  =" .$sXSL ."\n";

    $xslDoc->loadXML($sXSL);

    //$xslDoc->load('cv2fo.xsl');

    $xmlDoc = new DOMDocument();
    $xmlDoc->load('xml/CurrentCV.xml');

    $proc = new XSLTProcessor();
    $proc->registerPHPFunctions();


    $proc->importStylesheet($xslDoc);

    //$proc->setParameter  ('', 'catergories', 'Linux,Hardware,Software,WebDev');


    $foDoc = $proc->transformToDoc($xmlDoc);
    $foDoc->save('test-fo.xml');

    //echo $proc->transformToXML($xmlDoc);
