<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:php="http://php.net/xsl"
>
  <xsl:output method="xml" indent="yes"/>
  <xsl:key name="qualificationtypes" match="//qualification" use="@type"/>
  <xsl:template match="/">
    <xsl:param name="catergories" />
    <fo:root>

      
      <fo:layout-master-set>
        <fo:simple-page-master master-name="A4-portrait" page-height="29.7cm" page-width="21.0cm"
          margin="1cm">
          <fo:region-body/>
          <fo:region-after extent="1cm"/>
        </fo:simple-page-master>
      </fo:layout-master-set>

      <fo:page-sequence master-reference="A4-portrait">
        
        <fo:static-content flow-name="xsl-region-after" font-family="Times New Roman" font-size="8pt" text-align="right">
          <fo:block> <xsl:value-of select="cv/candidate/name"/>: <fo:page-number /> of <fo:page-number-citation ref-id="terminator"/>
          </fo:block>
        </fo:static-content>
        
        
        <fo:flow flow-name="xsl-region-body" font-size="11pt" font-family="Times New Roman" text-align="justify">

          <fo:block font-size="15pt" background-color="#DDD" font-family="Helvetica" text-align="center">
            <xsl:value-of select="cv/candidate/name"/>
          </fo:block>

          <xsl:for-each select="cv/candidate/address/*">
            <fo:block text-align="center">
              <xsl:value-of select="."/>
            </fo:block>

          </xsl:for-each>



          <fo:block>
            <fo:table table-layout="fixed" width="100%" background-color="white">
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-body>
                <fo:table-row>
                  <fo:table-cell>
                    <fo:block>
                      <xsl:value-of select="cv/candidate/tel"/>
                    </fo:block>
                  </fo:table-cell>
                  <fo:table-cell>
                    <fo:block text-align="center">
                      <xsl:value-of select="cv/candidate/mobile"/>
                    </fo:block>
                  </fo:table-cell>
                  <fo:table-cell>
                    <fo:block text-align="right">
                      <xsl:value-of select="cv/candidate/email"/>
                    </fo:block>
                  </fo:table-cell>
                </fo:table-row>
              </fo:table-body>
            </fo:table>
          </fo:block>

          <fo:block padding-before="12pt">
            <xsl:for-each select="cv/profile/item[contains($catergories,category)]/description">
              <xsl:value-of select="."/>
              <xsl:text> </xsl:text>
            </xsl:for-each>
          </fo:block>

          
          
          <fo:block keep-with-next="always" background-color="#DDD" font-family="Helvetica" font-size="13pt" text-align="left" padding-before="12pt"
            >Work Experience</fo:block>
          <fo:block>
            <fo:table table-layout="fixed" width="100%" background-color="white">
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-body>
                <xsl:for-each select="cv/experience">
                  <xsl:sort select="date/@year" order="descending" />
                  <xsl:sort select="date/@month" order="descending" />
                  <fo:table-row keep-with-next="always">
                    <fo:table-cell  padding-before="13pt">

                      <fo:block  text-align="left"  padding-right="13pt">
                        <xsl:value-of select="organisation"/>
                      </fo:block>
                    </fo:table-cell>
                    <fo:table-cell padding-before="13pt">
                      <fo:block >
                        <xsl:for-each select="date">
                          <xsl:sort select="." order="ascending" />
                          <xsl:if test="@month = 01">Jan</xsl:if>
                          <xsl:if test="@month = 02">Feb</xsl:if>
                          <xsl:if test="@month = 03">Mar</xsl:if>
                          <xsl:if test="@month = 04">Apr</xsl:if>
                          <xsl:if test="@month = 05">May</xsl:if>
                          <xsl:if test="@month = 06">June</xsl:if>
                          <xsl:if test="@month = 07">July</xsl:if>
                          <xsl:if test="@month = 08">Aug</xsl:if>
                          <xsl:if test="@month = 09">Sept</xsl:if>
                          <xsl:if test="@month = 10">Oct</xsl:if>
                          <xsl:if test="@month = 11">Nov</xsl:if>
                          <xsl:if test="@month = 12">Dec</xsl:if>
                          
                          <xsl:text> </xsl:text>
                          <xsl:value-of select="@year" />
                          <xsl:if test="position() &lt; last()">
                            <xsl:text> - </xsl:text>
                          </xsl:if>
                        </xsl:for-each>
                      </fo:block>
                    </fo:table-cell>
                    <fo:table-cell  padding-before="13pt">
                      <fo:block>
                        <xsl:value-of select="title"/>
                      </fo:block>
                    </fo:table-cell>
                  </fo:table-row>
                  <xsl:if test="count(item[contains($catergories,category)])">
                  <fo:table-row  keep-with-previous="always">
                    <fo:table-cell number-columns-spanned="3">
                      <fo:list-block
                        provisional-distance-between-starts="18pt" provisional-label-separation="3pt">
                        <xsl:for-each select="item[contains($catergories,category)]">
                          <fo:list-item keep-with-previous="always">
                            <fo:list-item-label end-indent="label-end()">
                              <fo:block>&#x2022;</fo:block>
                            </fo:list-item-label>
                            <fo:list-item-body start-indent="body-start()">
                              <fo:block>
                                <xsl:value-of select="description"/>
                              </fo:block>
                            </fo:list-item-body>
                          </fo:list-item>
                        </xsl:for-each>
                      </fo:list-block>
                    </fo:table-cell>
                  </fo:table-row>
                  </xsl:if>
                </xsl:for-each>
              </fo:table-body>
            </fo:table>
          </fo:block>





          <fo:block keep-with-next="always" background-color="#DDD" font-family="Helvetica" font-size="13pt" text-align="left" padding-before="12pt"
            >Education / Qualifications</fo:block>


          <fo:block>
            <fo:table table-layout="fixed" width="100%" background-color="white">
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="67%"/>

              <fo:table-body>
                <xsl:for-each select="cv/educationitem">
                  <fo:table-row >
                    <fo:table-cell padding-before="3pt">

                      <fo:block>
                        <xsl:value-of select="organisation"/>
                      </fo:block>
                    </fo:table-cell>
                    <fo:table-cell padding-before="3pt">
                      <fo:block>
                        <xsl:for-each select="date">
                          <xsl:sort select="." order="ascending" />
                          <xsl:if test="@month = 01">Jan</xsl:if>
                          <xsl:if test="@month = 02">Feb</xsl:if>
                          <xsl:if test="@month = 03">Mar</xsl:if>
                          <xsl:if test="@month = 04">Apr</xsl:if>
                          <xsl:if test="@month = 05">May</xsl:if>
                          <xsl:if test="@month = 06">June</xsl:if>
                          <xsl:if test="@month = 07">July</xsl:if>
                          <xsl:if test="@month = 08">Aug</xsl:if>
                          <xsl:if test="@month = 09">Sept</xsl:if>
                          <xsl:if test="@month = 10">Oct</xsl:if>
                          <xsl:if test="@month = 11">Nov</xsl:if>
                          <xsl:if test="@month = 12">Dec</xsl:if>
                          
                          <xsl:text> </xsl:text>
                          <xsl:value-of select="@year" />
                          <xsl:if test="position() &lt; last()">
                            <xsl:text> - </xsl:text>
                          </xsl:if>
                        </xsl:for-each>
                      </fo:block>
                    </fo:table-cell>
                  </fo:table-row>
                  <xsl:for-each select="qualification[generate-id(.)=generate-id(key('qualificationtypes', @type)[1])]">
                  <fo:table-row>
                    <fo:table-cell number-columns-spanned="2">
                      <fo:block>
                        <fo:inline><xsl:value-of select="@type"/>: </fo:inline>
                       
                          <xsl:for-each select="key('qualificationtypes', @type)">
                            <xsl:sort select="@grade"/>
                            <xsl:value-of select="@title"/>
                            <xsl:if test="@grade"> (<xsl:value-of select="@grade"/>)</xsl:if>
                            <xsl:if test="position() &lt; last()">
                              <xsl:text>,  </xsl:text>
                            </xsl:if>
                          </xsl:for-each>
                          <xsl:text>.</xsl:text>
                        
                      </fo:block>
                    </fo:table-cell>
                  </fo:table-row>
                  </xsl:for-each>
                  </xsl:for-each>
              </fo:table-body>
            </fo:table>
          </fo:block>




          <fo:block keep-with-next="always" background-color="#DDD" font-family="Helvetica" font-size="13pt" text-align="left" padding-before="12pt"
            >References</fo:block>
          <fo:block>
            <fo:table table-layout="fixed" width="100%" background-color="white">
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-column column-width="proportional-column-width(1)"/>
              <fo:table-body>
                  <fo:table-row>
                    <xsl:for-each select="cv/referee">
                      <fo:table-cell  padding-before="3pt">
                        <fo:block  text-align="left"  padding-right="13pt">
                          <xsl:value-of select="@name"/>
                        </fo:block>
                      </fo:table-cell>
                    </xsl:for-each>
                  </fo:table-row>
                <fo:table-row>
                  <xsl:for-each select="cv/referee">
                    <fo:table-cell>
                      <fo:block  text-align="left"  padding-right="13pt">
                        <xsl:value-of select="@title"/>
                      </fo:block>
                    </fo:table-cell>
                  </xsl:for-each>
                </fo:table-row>
                
                <fo:table-row>
                  <xsl:for-each select="cv/referee">
                    <fo:table-cell>
                      <fo:block  text-align="left"  padding-right="13pt">
                        <xsl:value-of select="organisation"/>
                      </fo:block>
                    </fo:table-cell>
                  </xsl:for-each>
                </fo:table-row>
                <fo:table-row>
                  <xsl:for-each select="cv/referee">
                    <fo:table-cell>
                      <xsl:for-each select="address/line">
                        <fo:block  text-align="left"  padding-right="13pt">
                           <xsl:value-of select="."/>
                        </fo:block>
                      </xsl:for-each>
                    </fo:table-cell>
                  </xsl:for-each>
                </fo:table-row>
                <fo:table-row>
                  <xsl:for-each select="cv/referee">
                    <fo:table-cell>
                      <fo:block  text-align="left"  padding-right="13pt">
                        <xsl:value-of select="email"/>
                      </fo:block>
                    </fo:table-cell>
                  </xsl:for-each>
                </fo:table-row>
                <fo:table-row>
                  <xsl:for-each select="cv/referee">
                    <fo:table-cell>
                      <fo:block  text-align="left"  padding-right="13pt">
                        <xsl:value-of select="tel"/>
                      </fo:block>
                    </fo:table-cell>
                  </xsl:for-each>
                </fo:table-row>
              </fo:table-body>
            </fo:table>
          </fo:block>
          
          <fo:block id="terminator"/>
        </fo:flow>
    
      </fo:page-sequence>
    </fo:root>
  </xsl:template>
</xsl:stylesheet>
