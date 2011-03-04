<?xml version="1.0" encoding="windows-1251"?>
<rss version="2.0">
  <channel>
    <title>{{title|default('RSS-feed')}}</title>
    <link>{{link}}</link>
    <description>{{description|default('Последние новости сайта')}}</description>
{% for i in items %} 
    <item>
      <title>{{i.title|e}}</title>
	  <guid>{{i.link}}</guid>
      <link>{{i.link}}</link>
      <description>{{i.description|e}}</description>
      <pubDate>{{i.pubdate}}</pubDate>
    </item>
{% endfor %} 
  </channel>
</rss>
