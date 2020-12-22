<?php

session_start();

if($_SESSION['loggedIn'] != "adminIN"){
    header("Location:/login.php");
}

require "header.php";
include('crawl_fns.php');
include('telugu_parser.php');

$language = "english";

//fix error for undefined index
$sunset = isset($_POST['lapseOptions']);
$depth = isset($_POST['depthOptions']);

$crawlURL = "crawlurl";

if($sunset === 'infinite') { //if drop down is set to infinite, send 0 to inserturlDB
    $sunset = 0;
}
elseif($sunset === 'day') {//if drop down is set to day, send 1 to inserturlDB
    $sunset = 1;
}
elseif($sunset === 'week') {//if drop down is set to week, send 7 to inserturlDB
    $sunset = 7;
}
elseif($sunset === 'month') {//if drop down is set to month, send 30 to inserturlDB
    $sunset = 30;
}
elseif($sunset === 'year') {//if drop down is set to year, send 365 to inserturlDB
    $sunset = 365;
}

//get admin url input from textarea*************************
if(isset ($_POST["crawlInput"])) {

    $crawlInput = explode(" ", $_POST["crawlInput"]);

    if(isset ($_POST['langRadio'])){
        $language = $_POST['langRadio'];

        if($language == "english") {
            $crawlURL = "crawlurl";
        }
        else if($language == "telugu") {
            $crawlURL = "crawlurl";
        }
    }

    foreach ($crawlInput as $input) {
        if (checkURLExists($input, $crawlURL) == true) {
            //if the url exists, it will not crawl.
            $messagePrompt = "Duplicate Found! Not added to database.";
        } else {
            // $messagePrompt = "CRAWLING.  Please wait, prompt will appear when finished.";
            //  //currently broken, doesn't proc before crawl
            crawl($input, $language, $sunset, $depth);
            echo "<script type='text/javascript'>alert('Crawl is finished!!')</script>";
        }

    }

}

function get_url_contents($url){
    $crl = curl_init();
    curl_setopt ($crl, CURLOPT_URL,$url);
    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);

    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}

function crawl($input, $language, $sunset, $depth){
    $seen = array();
    global $crawlURL;
    if(($depth == 0) or (in_array($input, $seen))){
        return;
    }

    $html = get_url_contents($input); //grab ALL html style tags
    $stripReady = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $html); //add space after open/close HTML tag
    $strippedHTML = strip_tags($stripReady); //php function to strip html tags
    if ($language === 'english') {
        $cleanCrawl = sanitizeInput($strippedHTML); //run function to clean up miscellaneous characters, alphanumeric numbers etc
        $text = explode(" ", $cleanCrawl);  //split up html content
        foreach ($text as $engWord) {  //for each "split"
            //http://www.gamefaqs.com/ps2/516587-kingdom-hearts/faqs/20007  //using this to crawl

            checkWordEngExists($engWord); //check if word exists in database
            if (checkWordEngExists($engWord) == false) {  //if it does not exist, enter into the database
                insertEngTXTToDB($engWord);
            }
        }
    }
    if ($language === 'telegu') {
        $cleanCrawl = sanitizeTelInput($strippedHTML);
        $text = explode(" ", $cleanCrawl);  //split the words by a space
        foreach ($text as $telWord) {
            checkWordTelExists($telWord); //check if word (or characters) exists in database
            if (checkWordTelExists($telWord) == false) {  //if it does not exist, enter into the database
                if(inTelRange($telWord) == true) {
                    insertTelTXTToDB($telWord);
                }
            }
        }
    }

    insertURLToDB($input, $sunset, $crawlURL);

    //check depth by putting URLS found into an array, and crawl new url found, by depth.
    if ($html) {
        //pull URL from file
        $stripped_file = strip_tags($html, "<a>");
        //check URL
        preg_match_all("/<a[\s]+[^>]*?href[\s]?=[\s\"\']+" . "(.*?)[\"\']+.*?>" . "([^<]+|.*?)?<\/a>/", $stripped_file, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $href = $match[1];
            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($href, array('path' => $path));
                } else {
                    $parts = parse_url($href);
                    //Error undefined add in isset for 123 and 127
                    $href = isset($parts['scheme']) . $input;
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= isset($parts['host']);
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path; //new url to crawl
                }
            }
            crawl($href, $language, $sunset, $depth-1);
        }
       // crawl($href, $radioButton, $sunset, $depth-1);  //run through the process again, but with a new depth.
    }
}

?>

<header>
    <link href="crawl_style.css" rel="stylesheet" type="text/css">
</header>
<div id="container">
    <div id="body">
        <div id="page_header">
            <h1>Enter a URL for crawling</h1>
            To enter multiple, <b>please separate each URL by a space.</b>
        </div>
        <!--Fix error for 151 using isset -->
        <span class="userMessage" id="errorMessage"><br/><?php echo (isset($messagePrompt));?></span>

        <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" id="crawlForm">
            <textarea id="crawlInput" name="crawlInput" placeholder="www.example.com www.anotherexample.com"></textarea></br>
            <select id="depthOptions" name="depthOptions" required>
                <option value="">Depth...</option>
                <option value="1" selected="selected">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <select id="lapseOptions" name="lapseOptions" required>
                <option value="" selected="selected">Time Lapse...</option>
                <option value="infinite">Infinite</option>
                <option value="day" >1 day</option>
                <option value="week">1 week</option>
                <option value="month">1 month</option>
                <option value="year">1 year</option>
            </select>

            <!--Fix error for 176 and 177 using isset -->
            <input type="radio" name="langRadio" value="english" id="radioEng" required <?php if(isset($_POST['langRadio']) === 'eng') { print ' checked="checked"'; } ?> checked/><label class="radio">English</label>
            <input type="radio" name="langRadio" value="telugu" id="radioTel" required <?php if(isset($_POST['langRadio']) === 'tel') { print ' checked="checked"'; } ?>/><label class="radio">Telugu</label>

            <input type="submit" value="Crawl" id="crawlFormButton"/></form>
    </div>
</div>
<?php
require "footer.php";
?>