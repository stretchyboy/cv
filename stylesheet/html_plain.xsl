<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	
	<xsl:template match="/">
		<xsl:param name="cat" />
		<html>
			<style type="text/css">
				body {	font-size: 10pt; font-family: "Times New Roman", serif; width:595px; text-align:justify;}
				h1 {	font-size: 14pt; font-family: Arial, sans-serif; text-align:center;}
				h2 {	font-size: 12pt; font-family: Arial, sans-serif; text-align:left;}
				ul { margin-left:-20px; }
				.organisationname {float:left; font-family: Arial, sans-serif; font-weight: bold; width:38%; padding-right:2%; padding-top:15px; text-align:left;}
				.dates {float:left; width:28%; padding-right:2%;  padding-top:15px;}
				.title {float:left; width:30%;  padding-top:15px;}
				.items {clear:both;}
				.address {text-align:center;}
				.tel {float:left; text-align:left; width:33%;}
				.mobile {float:left; text-align:center; width:34%;}
				.email {float:left; text-align:right; width:33%; margin-bottom:20px;}	
			</style>
			<body>
				<h1>
					<xsl:value-of select="cv/candidate/name"/>
				</h1>
				<p class="address">
					<xsl:for-each select="cv/candidate/address/*">
						<xsl:value-of select="."/>
						<xsl:text> </xsl:text>
					</xsl:for-each>
				</p>
				<div class="tel"><xsl:value-of select="cv/candidate/tel"/></div>
    <div class="mobile"><xsl:value-of select="cv/candidate/mobile"/></div>
    <div class="email"><xsl:value-of select="cv/candidate/email"/></div>
    			
				<h2>Profile</h2>
				<p> <!-- Paragraph  instead-->
					<xsl:for-each select="cv/item[@type='Profile']">
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
							<xsl:for-each select="item[contains(@catergories,$cat)]">
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