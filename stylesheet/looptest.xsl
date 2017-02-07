<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:key name="categories" match="//cv/experience/item" use="category"/>
	<!--<xsl:key name="products" match="product" use="region"/>-->
	<xsl:param name="categoryfilter"><categoryfilter><category>PHP</category><category>System Design</category></categoryfilter></xsl:param>
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>
	
	
	<!-- Template for our "products" rule -->
	<xsl:template match="cv">
		<html>
			<body>
				<!--<xsl:for-each select="$categoryfilter/categoryfilter/category">
					<xsl:variable name="CurrentCategory" select="." />
					<h3><xsl:value-of select="$CurrentCategory"/></h3>
					<xsl:for-each select="//cv/experience/item/category[text()=$CurrentCategory/category/text()]/..">
						<p><xsl:value-of select="description"/> <xsl:value-of select="description"/><xsl:text> </xsl:text></p>
					</xsl:for-each>
					
				</xsl:for-each>
				-->
				<!--<xsl:for-each select="//cv/experience/item/category[text()='PHP']/..">
					<p><xsl:value-of select="description"/> <xsl:value-of select="description"/><xsl:text> </xsl:text></p>
				</xsl:for-each>
				-->
				<!--
				<xsl:for-each select="$categoryfilter/category">
					<xsl:variable name="CurrentCategory" select="." />
					<h3><xsl:value-of select="$CurrentCategory"/></h3>
					
					<xsl:for-each select="key('categories',  $CurrentCategory)">
						<p><xsl:value-of select="description"/><xsl:text> </xsl:text></p>
					</xsl:for-each>
					
				</xsl:for-each>
				-->
				<!--<xsl:for-each select="//product[generate-id(.)=generate-id(key('products',region))]">
				<xsl:sort select="name" order="ascending"/>   
				<h3><xsl:value-of select="region"/> region</h3>
				-->
				
				<!--<p>
					<xsl:value-of select="profile"/>
				</p>-->
			<!---	<p>
					<xsl:value-of select="key('categories',  'PHP')/description "/>
				</p>
				
				
			-->
				<!--
				<xsl:for-each select="$categoryfilter/*">
					<h3><xsl:value-of select="."/> category</h3>
					<xsl:for-each select="key('categories',.)">
						<p><xsl:value-of select="description"/></p>
					</xsl:for-each>
				</xsl:for-each>
				-->
				
				<xsl:for-each select="experience/item[generate-id(.)=generate-id(key('categories', category))]">
					<h3><xsl:value-of select="category"/> category</h3>
					<xsl:for-each select="key('categories', category)">
					<p><xsl:value-of select="description"/></p>
					</xsl:for-each>
				</xsl:for-each>
				
				
				<!--
				<p>
					<xsl:for-each select="experience/item">
						<xsl:value-of select="."/><xsl:text> </xsl:text>	
					</xsl:for-each>
				</p>
				-->
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>