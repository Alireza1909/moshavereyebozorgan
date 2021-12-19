<?php
$question = ' در اینجا سوال خود را بپرسید';
$msg = 'این یک پاسخ نمونه است';
$en_name = 'hafez';
$fa_name = 'حافظ';
$message_array= array();
$msg1 = fopen("messages.txt" , "r");
$counter = 0;
while(! feof($msg1)){
    $message_array[$counter]=fgets($msg1);
    $counter++;
}

$shakhsiat=json_decode(file_get_contents("people.json"));
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $question = $_POST["question"];
    $en_name=$_POST["person"];
    $khorak=$question.$en_name;//khorak tarif mishavad ta khrooji be q va name bastegi dashte bashad
    $tanazor=hash('adler32',$khorak);//hash
    $tanazor=hexdec($tanazor);//tabdile hex be decimal
    $tanazor=$tanazor%16;//tabdil be yek adad dar peymane 16
    $aya="/^آیا/iu";
    if ($question==""){
        $msg="سوال خود را بپرس!";
    }
    else if ( preg_match($aya,$question) AND (preg_match("/\?$/i",$question) OR preg_match("/\؟$/i",$question))){
        $msg=$message_array[$tanazor];
    }
    else{
        $msg="سوال به درستی پرسیده نشده است!";
    }
    foreach($shakhsiat as $key => $value) {
        if ($key == $en_name){
            $fa_name=$value;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                $shakhsiat=json_decode(file_get_contents("people.json"));
                foreach($shakhsiat as $key => $value) {
                    if ($key == $en_name){
                        echo "<option value= $key selected> $value </option>";
                    }
                    else{
                        echo "<option value= $key > $value </option>";
                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>