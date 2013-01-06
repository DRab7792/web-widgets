<?php
date_default_timezone_set('America/New_York');
function cmp($a, $b){
	if (strtotime($a['pubDate']) == $b['pubDate']) {
        return 0;
    }
    return (strtotime($a['pubDate']) > strtotime($b['pubDate'])) ? -1 : 1;
}

 function uncdata($xml)
    {
        // States:
        //
        //     'out'
        //     '<'
        //     '<!'
        //     '<!['
        //     '<![C'
        //     '<![CD'
        //     '<![CDAT'
        //     '<![CDATA'
        //     'in'
        //     ']'
        //     ']]'
        //
        // (Yes, the states a represented by strings.) 
        //

        $state = 'out';

        $a = str_split($xml);

        $new_xml = '';

        foreach ($a AS $k => $v) {

            // Deal with "state".
            switch ( $state ) {
                case 'out':
                    if ( '<' == $v ) {
                        $state = $v;
                    } else {
                        $new_xml .= $v;
                    }
                break;

                case '<':
                    if ( '!' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                 case '<!':
                    if ( '[' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![':
                    if ( 'C' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![C':
                    if ( 'D' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![CD':
                    if ( 'A' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![CDA':
                    if ( 'T' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![CDAT':
                    if ( 'A' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case '<![CDATA':
                    if ( '[' == $v  ) {


                        $cdata = '';
                        $state = 'in';
                    } else {
                        $new_xml .= $state . $v;
                        $state = 'out';
                    }
                break;

                case 'in':
                    if ( ']' == $v ) {
                        $state = $v;
                    } else {
                        $cdata .= $v;
                    }
                break;

                case ']':
                    if (  ']' == $v  ) {
                        $state = $state . $v;
                    } else {
                        $cdata .= $state . $v;
                        $state = 'in';
                    }
                break;

                case ']]':
                    if (  '>' == $v  ) {
                        $new_xml .= str_replace('<','&lt;',
                                    str_replace('>','&gt;',
                                    str_replace('"','&quot;',
                                    str_replace('&','&amp;',
                                    $cdata))));
                        $state = 'out';
                    } else {
                        $cdata .= $state . $v;
                        $state = 'in';
                    }
                break;
            } // switch

        }

        //
        // Return.
        //
            return $new_xml;

    }

$urls = array("http://hosted.ap.org/lineups/TOPHEADS-rss_2.0.xml?SITE=VOICESD&SECTION=HOME","http://feeds2.feedburner.com/thenextweb","http://rss.sciam.com/ScientificAmerican-News","http://www.engadget.com/rss.xml","http://feeds.feedburner.com/abduzeedo?format=xml");
$arrs = array();
$iconUrls = array();
$count = 0;
foreach($urls as $url){
	$curData = file_get_contents($url);
	$curData = uncdata($curData);
	// $curData = str_replace("<![CDATA[", '', $curData);
	// $curData = str_replace("]]>", '', $curData);
	// if ($count==3) {file_put_contents("engadget", $curData);}
	$arr[] = json_decode(json_encode((array) simplexml_load_string($curData)), 1);

	$count++;
}
$totalItems = array();
foreach ($arr as $cur){
	$curIcon = (isset($cur['channel']['image'])) ? $cur['channel']['image']['url'] : '';
	$iconUrls[] = $curIcon;
	foreach ($cur['channel']['item'] as $article){
		$article['icon'] = $curIcon;
		$totalItems[] = $article;
	}
}
usort($totalItems, "cmp");
// print_r ($iconUrls);
?>
<html>
	<head>
		<title>NEWS</title>
		<style type="text/css">
			@font-face
			{
				font-family: melbourne;
				src: url('fonts/MelbourneRegular.ttf');
				font-weight:bold;
			}
			@font-face
			{
				font-family: upperEastSide;
				src: url('fonts/UpperEastSide.ttf');
				font-weight:bold;
			}
			.corner{
				float:right;
				width:15%;
				height:10%;
			}
			.buttons{
				display: none;
				float:right;
			}
			.title img{
				height:36px;
			}
			.title{
				display: block;
				position: absolute;
				margin-left: 5%;
				font-size:36px;
				bottom: 79%;
				height:0px;
				margin-right:0px;
				font-family: "upperEastSide";
			}
			.hidden{
				color: white;
				opacity: 0;
			}
			.window{
				display: block;
				position: absolute;
				font-size: 20px;
				height: 0px;
				width:82%;
				overflow-x: hidden; 
				top:28%;
				overflow-y: scroll;
				font-family: "melbourne";
			}
			.container:hover {
				border:1px solid black;
			}
			.divide{
				height:2px;
				background-color: black;
				top:22%;
				padding-right:3%;
				width:80%;
				position:absolute;
			}
			.container{
				margin-left:auto;
				margin-right:auto;
				display: block;
				padding-left: 3%;
				border:1px solid white;
				width:30%;
				position:absolute;
				left:35%;
				height:60%;
			}
			.article{
				background-color: #bbbbbb;
				overflow-x:hidden;
				height: 50%;
				overflow-y:hidden; 
			}
			.email p{
				margin-bottom: 0px;
				margin-top: 0px;
			}
			.alt {
				background-color: white;
			}
			.articleTitle{
				overflow: hidden;
				height: 33px;
				font-weight:bold; 
				font-size: 30px;
				width: 100%;
				margin: 0px;
				font-weight:bold;
			}
			.description{
			    display: block;
			    overflow: hidden;
				font-size: 20px;
				height: 52px;
			}
			.time{
			    text-align: right;
				width: 100%;
				height: 15px;
				font-size: 12px;
				position: relative;
				margin: 0;
				float: right;
			}
			.time img{
				height:12px;
				margin-right: 10px;
			}
			::-webkit-scrollbar {
			    width: 15px;
			}
			 
			::-webkit-scrollbar-track {
			    background-color: white;
			}
			 
			::-webkit-scrollbar-thumb {
				border-radius: 5px;
				height: 30px;
			    background-color: #aaa;
			}
			.description img{
				height:100%;
				margin-right:10px;
				max-width: 30%;
				display: inline-block;
				float: left;
			}
			.description p{
				margin:0;
			}
			a{
				text-decoration: none;
				color: black;
			}
		</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
		<script>
			$(document).ready(function(){ setTimeout(animation,<?php echo $_GET['delay']?>); });
			function animation(){
				$(".title").removeClass("hidden").animate({height: "16%"}, <?php echo $_GET['speed']?>);
				var fontSize = ($('.container').height()*.16) - 5;
				//alert (fontSize.toString()+"px");
				$('.title').css('font-size',(fontSize.toString()+"px"));
				$('.title img').css('height',fontSize-10);
				var off = $('.articleTitle').height()+$('.time').height();
				var articSize = ($('.container').height()*.65*.5);
				$('.description').height(articSize-off);
				//$(".divide").removeClass("hidden").animate({top:"6%"}, <?php echo $_GET['speed']?>);
				var newCss = {};
				//newCss['top'] = '11%';
				newCss['height'] = $('.container').height()*.65;
				$(".window").removeClass("hidden").animate(newCss, <?php echo $_GET['speed']?>);
				$(".window").css("overflow-y","auto");
			}
			
		</script>
	</head>
	<body>
		<div class="container">
			<div class="corner" onMouseOut="$('.buttons').css('display','none')" onMouseOver="$('.buttons').css('display','block')">
				<img class="buttons" src="images/close.jpg" width="30px"  id="close" onClick="$('.container').hide()">
			</div>
			<div class='title hidden'>NEWS<img src="images/news.png"></div>
			<div class="divide"></div>
			<div class="window hidden" style="overflow-y:auto;" >
				<?php $count=0; foreach($totalItems as $article){ ?>
				<a href="<?php echo $article['link'];?>" target="__blank"><div class="article <?php echo  ($count%2==1)?'alt':'';?>">
					<p class="articleTitle"><?php print_r ($article['title']);?></p>
					<div class="description"><?php echo strip_tags($article['description'],"<img>");?></div>
					<p class="time"><?php if ($article['icon']!=''){?><img src="<?php echo $article['icon'];?>"><?php }?><?php $time = strtotime($article['pubDate']); echo (date("j")==date("j", $time)) ? date("g:i a",intval($time)): date("g:i a M j",intval($time));?></p>
				</div></a>
				<?php $count++;}?>
			</div>
		</div>			
	
	</body>
</html>
