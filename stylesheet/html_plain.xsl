<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output 
		method="html" 
		omit-xml-declaration="yes"
		encoding="UTF-8" 
		indent="yes"/>
	
	<xsl:template match="/">
		<xsl:param name="cat"/>
		<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;</xsl:text>
		<html lang="en">
			<head>
				 <!--<meta charset="UTF-8" />-->
				 <link rel="stylesheet" type="text/css" href="../stylesheet/reset.css" />
				 <link rel="stylesheet" type="text/css" href="../stylesheet/plain.css" />
				 <title><xsl:value-of select="cv/candidate/name"/> - CV</title>
			</head>
			<body>
				<section>
					<h1>
						<xsl:value-of select="cv/candidate/name"/>
					</h1>
        			<div class="item">
		        		<p class="address">
							<xsl:for-each select="cv/candidate/address/*">
								<xsl:value-of select="."/>
								<xsl:if test="position() &lt; last()">
									<xsl:text>, </xsl:text>
								</xsl:if>
							</xsl:for-each>
						</p>
					
					    <div class="mobile"><xsl:value-of select="cv/candidate/mobile"/></div>
					    <div class="email"><xsl:value-of select="cv/candidate/email"/></div>
					    <div class="twitter">@<xsl:value-of select="cv/candidate/twitter"/></div>
					</div>
				</section>
				
				<section>
					<h2>Profile</h2>
			        <div class="item">
						<xsl:for-each select="cv/profile/item[contains($catergories,@category)]/description">
							<xsl:value-of select="."/><xsl:text> </xsl:text>
				        </xsl:for-each>
					</div>
				</section>
				
				<section>
					<h2>Work Experience</h2>
					<xsl:for-each select="cv/experience/date[@year &gt;= $from]/..">
						<xsl:sort select="date[1]/@year" data-type="number" order="descending"/>
						<xsl:sort select="date[1]/@month" data-type="number" order="descending"/>
						<div class="item">
							<div class="infobar">
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
									<xsl:if test="@type != 'Paid'">
										<xsl:text> (</xsl:text><xsl:value-of select="@type"/><xsl:text>)</xsl:text>
									</xsl:if>
								</div>
							</div>
						
							<xsl:if test="summary">
								<xsl:if test="string-length(summary)">
									<div class="summary">
										<xsl:value-of select="summary"/>
									</div>
								</xsl:if>
							</xsl:if>
							<xsl:if test="date[@year &gt;=$details]">
								<xsl:if test="item[contains($catergories,@category)]/description">
									<div class="items">
										<ul>
											<xsl:for-each select="item[contains($catergories,@category)]/description">
												<li>
													<xsl:value-of select="."/>
												</li>
											</xsl:for-each>
										</ul>
									</div>
								</xsl:if>
							</xsl:if>
						</div>
					</xsl:for-each>
				</section>
				
				<section>
					<h2>Education / Qualifications</h2>
					<xsl:for-each select="cv/educationitem/qualification[@level &gt; 2]/..">
						<xsl:sort select="date"/>
						<div class="item">
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
						</div>
					</xsl:for-each>
				</section>
				
				<section>		
					<h2>References</h2>
					<div class="item">
						<xsl:for-each select="cv/referee">
							<xsl:sort select="name"/>
							<div class="referee">
								<h3><xsl:value-of select="@name"/></h3>
								<p>
									<xsl:value-of select="@title"/><br/>
									<xsl:value-of select="organisation"/>
								</p>
							
								<p >
									<xsl:for-each select="address/*">
										<xsl:value-of select="."/><br />
									</xsl:for-each>
									<xsl:value-of select="tel"/><br />
								    <xsl:value-of select="email"/>
					    		</p>
							</div>
						</xsl:for-each>
					</div>
				</section>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
