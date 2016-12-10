START
- Upload zipload.php into a public folder which content you want to get
- Give the folder permission 777

Make a zipped file of all files of the current folder:
http://urltofolder.com/zipload.php?cmd=zip

Download this zipped file
wget http://urltofolder.com/zip.zip

Delete all contents in current folder
http://urltofolder.com/zipload.php?cmd=delete

Upload a zipped file called "zip.zip" of all contents you want to have on the ftp:
http://urltofolder.com/zipload.php?cmd=unzip

STOP
- Delete zipload.php
- Give the folder permission 710