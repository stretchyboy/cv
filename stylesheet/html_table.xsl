<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	
	<xsl:template match="/">
		<xsl:param name="cat" />
		<html>
			<style type="text/css">
					body {					font-family: "Times New Roman", serif;}
					h1,h2,h3 {	font-family: Arial, sans-serif;}
					.organisationname {font-family: Arial, sans-serif; font-weight: bold;}
			</style>
			<body>
					<h1>
						<xsl:value-of select="cv/candidate/name"/>
					</h1>
					<p>
						<xsl:for-each select="cv/candidate/address/*">
							<xsl:value-of select="."/>
							<xsl:text> </xsl:text>
						</xsl:for-each>
					</p>
				
					<h2>Profile</h2>
					<ul>
						<xsl:for-each select="cv/item[@type='Profile']">
							<li>
								<xsl:value-of select="."/>
							</li>
						</xsl:for-each>
					</ul>
					<h2>Work Experience</h2>
					<table width="100%">
						<xsl:for-each select="cv/experience">
							<xsl:sort select="date"/>
							<tr>
								<td class="organisationname">
									<xsl:value-of select="organisation"/>
								</td>
								<td style="width:25%">
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
								</td>
								<td style="width:25%">
									<xsl:value-of select="title"/>
								</td>
						
							</tr>
							<tr>
								<td colspan="3">
									<ul>
										<xsl:for-each select="item[contains(@categories,$cat)]">
											<li>
												<xsl:value-of select="."/>
											</li>
										</xsl:for-each>
									</ul>
								</td>
							</tr>
						</xsl:for-each>
					</table>
				
					<h2>Education / Qualifications</h2>
					<table width="100%">
						<xsl:for-each select="cv/educationitem">
							<xsl:sort select="date"/>
							<tr>
								<td class="organisationname">
									<xsl:value-of select="organisation"/>
								</td>
								<td colspan="2">
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
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<ul>
										<xsl:for-each select="qualification">
											<li>
												<xsl:value-of select="@type"/> - <xsl:value-of select="@title"/> - <xsl:value-of select="@grade"/>
											</li>
										</xsl:for-each>
									</ul>
								</td>
							</tr>
						</xsl:for-each>
					</table>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>