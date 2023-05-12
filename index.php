<?php
/**?
 * 
 Youtubeにアップされているアルバムを分割してmp3で保存する 
 https://www.y2mate.com/jp/youtube-mp3/vL28_YGAFFY
　↑こういうのでMP３化
 ファイル名を master.mp3にして　$datDIR(output)以下に置く
 
0:00|B*tch Don't Kill My Vibe
3:56|Thinking About You
9:09|Passionfruit
14:40|New Light
21:06|Violet
26:36|Riot

タイムリストが載ってるはずだから、
それを上記のようなフォーマットに整形したテキストファイルを
$datDIR以下にdata.txtとして置く
" - "=>"|"

このプログラムにアクセスする
待ってれば outputフォルダ内に出力されている

320bps ３時間、３７ファイルでも待っていれば２分半で出来たので大抵のファイルは大丈夫だと思う。
あとは、MP3タグ入れるソフトで埋め込んで完成

//*******************************************************

 * 
 * 
 *https://github.com/thegallagher/PHP-MP3
こっちのプログラムはでかいファイルでは機能しなかったが

こっちは↓動作した。
https://github.com/falahati/PHP-MP3
ただしphp.iniをいじって実行メモリは５１２MB、タイムアウトの時間は１８０以上くらいにしないと無理

<usage>
 Strip ID3 tags from a MP3 file:MP3 
 ファイルから ID3 タグを取り除く
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->stripTags()->saveFile("new.mp3");

Cut a MP3 file to extract a 30sec preview starting at the 10th second:
最初の１０秒から３０秒間を切り出す
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->trim(10, 30)->saveFile("new.mp3");

Append memory stored MP3 data to the end of a MP3 file:
メモリに蓄積したMP3のデータをMP3の末尾に追加
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->append(\falahati\PHPMP3\MpegAudio::fromData(base64_decode("/BASE64-DAT/")))->saveFile("new.mp3");

Extracting MP3 file total duration:
再生時間の取得
echo \falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->getTotalDuration();
 
* 
 * 
 */

//************************************************************** SETTING*/

$datDIR="./source/";
$outputDIR=$datDIR."output/";
$sample=$datDIR."master.mp3";//元のMP3ファイル
$dataTXT="data.txt";//タイムと曲名　を記載したテキストファイル

//************************************************************** CODE*/

include("./src/MpegAudioFrameHeader.php");
include("./src/MpegAudio.php");

$time_start = microtime(true); //実行開始時間を記録する

if(!is_dir($outputDIR)){
    mkdir($outputDIR);
}

$d=file_get_contents($datDIR.$dataTXT);
$dx=explode("\n",$d);
array_push($dx,"\n");
$sA=0;
$fA=0;

$d= \falahati\PHPMP3\MpegAudio::fromFile($sample)->getTotalDuration();
$sL=(int)$d;//master.mp3ファイルの長さ（seconds）

$start=0;
$end=0;
$trackTImeSeconds=0;//このプログラムは指定した時間から何秒間を切り抜くかで指定する

$fileName="";
foreach ($dx as $key => $value) {
    $end=0;
    if(strpos($value,"|")!==false){
        $sd=explode("|",$value);
        $sC=timeColon2Seconds($sd[1]);
        if($key>0){
            $track=sprintf("%03d",$key);
            $start=$sA;
            $end=$sC;
            $trackTImeSeconds=$end-$start;
            $fileName=$name;
        }

        $sA=$sC;
        $name=$sd[0];

    }else{
        if($fA!=$sA){
            $track=sprintf("%03d",$key);
            $start=$sA;
            $end=$sL;
            $trackTImeSeconds=$end-$start;
            $fileName=$name;
            $fA=$sA;
        }
    }

    //cut
    if($end>0){
        echo $start."=>".$end."(".$trackTImeSeconds.")=> ".$track."_".convertFilenNme($fileName).".mp3<br>";
        \falahati\PHPMP3\MpegAudio::fromFile($sample)->trim($start, $trackTImeSeconds)->saveFile($outputDIR.$track."_".convertFilenNme($fileName).".mp3");
    }
    

}

echo "<hr>";
$time_end = microtime(true);
$time = $time_end - $time_start;
var_dump($time); //実行時間を出力する
echo "sec";
echo "sec";

//************************************************************** MY FUNCTION*/
/**
 * 
 *  00:01:00 => 60
 *  01:01:00 => 3660
 * @param string:$colonTIme
 * @return int
 */

function timeColon2Seconds($colonTIme){
    /// 2:53:18
    $digitH=0;
    $digitM=0;
    $digitS=0;
    if(strpos($colonTIme,":")!==false){
        $en=explode(":",$colonTIme);
        if(!empty($en[2])){
            $digitH=$en[0]*60*60;
            $digitM=$en[1]*60;
            $digitS=$en[2];
        }else{
            $digitM=$en[0]*60;
            $digitS=$en[1];  
        }
    }
    
    return $digitH+$digitM+$digitS;
}

/**
 * ファイル名に出来ない文字を全角にする
 * @param string:$value
 * @return string
 * 
 */
function convertFilenNme($value){
    $value=trim($value);
    $value = str_replace("\\", "￥", $value);
    $value = str_replace("/", "／", $value);
    $value = str_replace(":", "：", $value);
    $value = str_replace("*", "＊", $value);
    $value = str_replace("?", "？", $value);
    $value = str_replace("\"", "”", $value);
    $value = str_replace("<", "＜", $value);
    $value = str_replace(">", "＞", $value);
    $value = str_replace("|", "｜", $value);

    return $value;

}


?>