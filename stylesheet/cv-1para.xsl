<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html width="595px">
			<body >
			<div width="595px">
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
				<p>
					<xsl:for-each select="cv/item[@type='Profile']">
						<xsl:value-of select="."/>
						<xsl:text> </xsl:text>
					</xsl:for-each>
				</p>
				<h2>Skills</h2>
				<p>
					<xsl:for-each select="cv/item[@type='Skills']">
						<xsl:value-of select="."/>
						<xsl:text> </xsl:text>
					</xsl:for-each>
				</p>
				<h2>Work Experience</h2>
				<table width="100%">
					<xsl:for-each select="cv/experience">
					<xsl:sort select="date"/>
						<tr>
							<td style="font-weight:bold;">
								<xsl:value-of select="organisation"/>
							</td>
							<td>
								<xsl:for-each select="date">
									<xsl:value-of select="@month"/>
									<xsl:text> </xsl:text>
									<xsl:value-of select="@year"/>
									<xsl:text> - </xsl:text>
								</xsl:for-each>
							</td>
							<td>
								<xsl:value-of select="title"/>
							</td>
						
						</tr>
						<tr>
							<td colspan="3">
								<xsl:for-each select="item">
									<xsl:value-of select="."/>
									<xsl:text> </xsl:text>
								</xsl:for-each>
							</td>
						</tr>
					</xsl:for-each>
				</table>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>