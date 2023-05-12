# mp3youtube
# Youtubeにアップされているアルバムを分割してmp3で保存する 
 https://www.y2mate.com/jp/youtube-mp3/vL28_YGAFFY<br>
　↑こういうのでMP３化<br>
 ファイル名を master.mp3にして　$datDIR(output)以下に置く<br><br>
 <pre>
0:00|B*tch Don't Kill My Vibe
3:56|Thinking About You
9:09|Passionfruit
14:40|New Light
21:06|Violet
26:36|Riot
</pre>
タイムリストが載ってるはずだから、それを上記のようなフォーマットに整形したテキストファイルを$datDIR以下にdata.txtとして置く
" - "=>"|"

このプログラムにアクセスする
待ってれば outputフォルダ内に出力されている

320bps ３時間、３７ファイルでも待っていれば２分半で出来たので大抵のファイルは大丈夫だと思う。
あとは、MP3タグ入れるソフトで埋め込んで完成

***

 *https://github.com/thegallagher/PHP-MP3
こっちのプログラムはでかいファイルでは機能しなかったが

こっちは↓動作した。
https://github.com/falahati/PHP-MP3
ただしphp.iniをいじって実行メモリは５１２MB、タイムアウトの時間は１８０以上くらいにしないと無理

## <usage>
### Strip ID3 tags from a MP3 file:MP3 
 ファイルから ID3 タグを取り除く
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->stripTags()->saveFile("new.mp3");

### Cut a MP3 file to extract a 30sec preview starting at the 10th second:
最初の１０秒から３０秒間を切り出す
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->trim(10, 30)->saveFile("new.mp3");

### Append memory stored MP3 data to the end of a MP3 file:
メモリに蓄積したMP3のデータをMP3の末尾に追加
\falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->append(\falahati\PHPMP3\MpegAudio::fromData(base64_decode("/BASE64-DAT/")))->saveFile("new.mp3");

### Extracting MP3 file total duration:
再生時間の取得
echo \falahati\PHPMP3\MpegAudio::fromFile("old.mp3")->getTotalDuration();
 
* 
 * 
 */
