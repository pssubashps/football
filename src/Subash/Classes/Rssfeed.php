<?php
namespace Subash\Classes;

class Rssfeed
{

    public function getPlayerRssfeed($playername)
    {
        $returnArray = [];
        $xml = ("https://news.google.de/news/feeds?pz=1&cf=all&ned=LANGUAGE&hl=aus&q=".$playername."&output=rss");
        
        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($xml);
        
        // get elements from "<channel>"
        $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
        $returnArray['feed_title'] = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
        $returnArray['feed_link'] = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
        $returnArray['feed_desc'] = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
        
        $items = $xmlDoc->getElementsByTagName('channel')
            ->item(0)
            ->getElementsByTagName('item');
  
        foreach ($items as $key => $item) {
            $title = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
            $description = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
            $pubDate = $item->getElementsByTagName('pubDate')->item(0)->firstChild->nodeValue;
            $guid = $item->getElementsByTagName('guid')->item(0)->firstChild->nodeValue;
            
            $returnArray['item'][$key]['title'] = $title;
            $returnArray['item'][$key]['pubdate'] = $pubDate;
            /*
             * $returnArray['item'][$key]['description'] = $description;
             * $returnArray['item'][$key]['pubdate'] = $pubDate;
             * $returnArray['item'][$key]['guid'] = $guid;
             */
        }
        return $returnArray;
    }
}