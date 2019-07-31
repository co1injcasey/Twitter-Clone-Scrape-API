<?php
//require_once 'apikeys.php';

// if you dont want to scrape the website and only want to search pictures
// then comment the next two lines out and uncomment lines 17 - 19
$data = file_get_contents("https://twitter.com/search?q=atlanta&src=typd");
print $data;
//scrapes twitter for all content related to atlanta. but you can use whatever you want

$search = trim(htmlspecialchars($_REQUEST['search']));
if(!$search) $search = "";
$url = ("https://twitter.com/search?f=tweets&vertical=default&q=".urlencode($search)."&src=typd");
$proxiedUrl = "http://api.scraperapi.com?api_key=type_ur_key_here&url=".$url;
$data = file_get_contents($proxiedUrl);

// If you want to search twitter photos and not scrape the website, uncomment this
//print <<<EOF
//<form action=twitter.php><input name=search value="$search"><input type=submit></form>
//EOF;

$tweets = explode('<small class="time">', $data);
foreach($tweets as $tweet){
  if(strpos($tweet, 'pbs.twimg') == -1) continue;
  if (preg_match_all('#<a href="(/.*?)".*?AdaptiveMedia.*?(https://pbs.twimg.*?\.(png|jpg))#s',
     $tweet, $matches)) {
      $url = $matches[1][0];
      $pic = $matches[2][0];
      $map[$url] = $pic;
  }
}

foreach($map as $url=>$pic){
  $cloudimage = "https://anaixnggen.cloudimg.io/crop/300x300/x/" . $pic;
  print "<a href=http://twitter.com/$url><img width=width src='$cloudimage'></a>";
}
