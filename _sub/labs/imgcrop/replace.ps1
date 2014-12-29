$a = Get-Content output.txt
foreach($item in $a){
#Wait a bit
sleep -seconds 1

$filename="img_{0}_{1}_{2}.png" -f $item.split(',')[1], $item.split(',')[3], $item.split(',')[5]
$filepath="D:\projects\selenium-web-driver\src\downloadfiles\download\{0}" -f $filename
$url=$item.split(',')[6]

$webclient = New-Object System.Net.WebClient
$webclient.DownloadFile($url,$filepath)

"Done: Url {0} and file {1}" -f $url, $filename

}