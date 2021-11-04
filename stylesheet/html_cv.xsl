<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	
	<xsl:template match="/">
		<xsl:param name="cat"/>
		<html>
			<style type="text/css">
					
			</style>
			<body>
				<h1>
					<xsl:value-of select="cv/candidate/name"/>
				</h1>
        
        <table width="595px" border="0">
          <tr>
            <td colspan="3" align="center">
              <xsl:for-each select="cv/candidate/address/*">
                <xsl:value-of select="."/><xsl:text> </xsl:text>
              </xsl:for-each>
            </td>
          </tr>
          <tr>
            <td width="33%" align="left"><xsl:value-of select="cv/candidate/tel"/></td>
            <td width="34%" align="center"><xsl:value-of select="cv/candidate/mobile"/></td>
            <td width="33%" align="right"><xsl:value-of select="cv/candidate/email"/></td>
          </tr>
        </table>
          <!--
				<p class="address">
					<xsl:for-each select="cv/candidate/address/*">
						<xsl:value-of select="."/><xsl:text> </xsl:text>
					</xsl:for-each>
				</p>
				<div class="tel"><xsl:value-of select="cv/candidate/tel"/></div>
    <div class="mobile"><xsl:value-of select="cv/candidate/mobile"/></div>
    <div class="email"><xsl:value-of select="cv/candidate/email"/></div>
    			-->
				<h2>Profile</h2>
        <p>
				  <xsl:for-each select="cv/profile/item[contains(@categories,$cat)]/description">
					  <xsl:value-of select="."/><xsl:text> </xsl:text>
          </xsl:for-each>
				</p>
				
				<h2>Work Experience</h2>
						
				<xsl:for-each select="cv/experience">
					<xsl:sort select="date"/>
					<div class="organisationname">
						<xsl:value-of select="organisation"/>
					</div>
					<div class="dates">
						<xsl:for-each select="date">
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
							<xsl:value-of select="@year"/>
							<xsl:if test="position() &lt; last()">
								<xsl:text> - </xsl:text>
							</xsl:if>
						</xsl:for-each>
					</div>
					<div class="title">
						<xsl:value-of select="title"/>
					</div>
					<div class="items">
									
						<ul>
							<xsl:for-each select="item[contains(@categories,$cat)]/description">
								<li>
									<xsl:value-of select="."/>
								</li>
							</xsl:for-each>
						</ul>
						</div>
					</xsl:for-each>
					
					<h2>Education / Qualifications</h2>
						
					<xsl:for-each select="cv/educationitem">
						<xsl:sort select="date"/>
						<div class="organisationname">
							<xsl:value-of select="organisation"/>
						</div>
						<div class="dates">
							<xsl:for-each select="date">
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
								<xsl:value-of select="@year"/>
								<xsl:if test="position() &lt; last()">
									<xsl:text> - </xsl:text>
								</xsl:if>
							</xsl:for-each>
						</div>
						<div class="items">
							<ul>
								<xsl:for-each select="qualification">
									<li>
										<xsl:value-of select="@type"/> - <xsl:value-of select="@title"/><xsl:if test="@grade"> (<xsl:value-of select="@grade"/>)</xsl:if>
									</li>
								</xsl:for-each>
							</ul>
						</div>
					</xsl:for-each>
			
				</body>
			</html>
		</xsl:template>
	</xsl:stylesheet>
