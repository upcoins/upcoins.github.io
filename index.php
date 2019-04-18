<?php
#php3T version 1.2
############################################
#Copyright (C) 2001 Evan Schulz
#
#This program is free software; you can redistribute it and/or
#modify it under the terms of the GNU General Public License
#as published by the Free Software Foundation; either version 2
#of the License, or (at your option) any later version.
#
#Для тех, кто не понял - программа защищена лицензией и т.д. и т.п....
#
#Я нисколько не притендую на правообладание сией прогой, но думаю, что могу 
#выставить свое имя, как переводчика на русский язык :)
#
#Перевел Колян Клементьев (http://nknet.karelia.ru, http://petrochat.pp.ru)
#
###########################################
# Изменяем следующие параметры (заголовок, фон, изображение, сложность по умолчанию):
$pagetitle='Крестики - нолики';
$pagebackground='white';
$pageimage='title.gif';
$pageimagealt=$pagetitle;

$tblbackground='white';

$cellbackground='c0c0c0';

$ximage='x.gif';
$ximagealt='x';
$oimage='o.gif';
$oimagealt='o';

$defaultdifficulty='Нормально';
#####################
if (isset($new))
{
	session_start();
	unset($b);
	unset($turn);
	unset($cdiff);
	session_destroy();
}

session_start();
if (! isset($turn))
{
	session_register(turn);
	$turn=1;
	session_register(b);
	session_register(cdiff);
	if ($diff == 'e')
		$cdiff = 'Easy';
	elseif ($diff == 'n')
		$cdiff = 'Normal';
	elseif ($diff == 'i')
		$cdiff = 'Impossible';
	else
		$cdiff = $defaultdifficulty;
}
global $b;
global $gwin;
global $gover;
?>
<HTML><HEAD><TITLE><?php echo $pagetitle ?></TITLE></HEAD>
<BODY BGCOLOR="<?php echo $pagebackground ?>" TOPMARGIN="0">
<center><IMG SRC="<?php echo $pageimage ?>" ALT="<?php echo $pageimagealt ?>">
<BR>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" BGCOLOR="<?php echo $tblbackground ?>">
<TR><TD>
<TABLE BORDER="1" CELLPADDING="0" CELLSPACING="10">
<?php 
//take input if got input
if (isset($mv))
	$b[$mv]='x';

checkwin();
checkfull();


// calculate computers move
if ($gover <> 1 && $gwin == '' && $mv <> '')
{
	if ($cdiff == 'Easy')
	{
		comprand();
	}
	elseif ($cdiff == 'Normal')
	{
		compmove();
				
		if ($cmv == '')
		{
			comprand();
		}
	}
	elseif ($cdiff == 'Impossible')
	{
		compmove();
		if ($cmv == '')
		{
			if ($b[4] == '')
				$cmv=4;
			elseif ($b[0] == '')
				$cmv=0;
			elseif ($b[2] == '')
				$cmv=2;
			elseif ($b[6] == '')
				$cmv=6;
			elseif ($b[8] == '')
				$cmv=8;
			if ($cmv == '')
				comprand();
		}
		
	}
	$b[$cmv] = 'o';
}
// **********
checkwin();
checkfull();


// Print board
for ($i = 0; $i <= 8; $i++)
{
	if ($i == 0 || $i == 3 || $i == 6)
		print '<TR>';
	print '<TD WIDTH="75" HEIGHT="75" ALIGN="MIDDLE" VALIGN="MIDDLE" BGCOLOR="'.$cellbackground.'">';
	
	if ($b[$i] == 'x')
		print '<IMG SRC="'.$ximage.'" ALT="'.$ximagealt.'">';
	elseif ($b[$i] == 'o')
		print '<IMG SRC="'.$oimage.'" ALT="'.$oimagealt.'">';
	elseif ($gwin == '')
		print '<INPUT TYPE="SUBMIT" NAME="mv" VALUE="'.$i.'">';
	
	print '</TD>';
	if ($i == 2 || $i == 5 || $i == 8)
		print '</TR>';
}
// *************
?>
</TABLE></TD></TR></TABLE>
<?php
print '<FONT SIZE="-1">Сложность: '.$cdiff.'</FONT>';
if ($gwin == 'O' || $gwin == 'X')
	print "<P><B>$gwin победил!</B></P>";
elseif ($gover == 1)
	print "<P><B>Победил комп!</B></P>";
print '<P>Выберите сложность: <SELECT NAME="diff"><OPTION VALUE="e">Легко</OPTION><OPTION VALUE="n" SELECTED>Нормально</OPTION><OPTION VALUE="i">Невозможно</OPTION></SELECT><BR><INPUT TYPE="SUBMIT" NAME="new" VALUE="Начать новую игру">';
?>

</FORM><br><br><script language="JavaScript"> var id=43; var rnd=Math.random()*1000000000000; rnd=Math.round(rnd); var append="id="+id+"&rnd="+rnd; var str="<a href=http://nord.net.ru/banner/cgi-bin/redirect.cgi?"+append+">"; str+="<img border=0 width=468 height=60 src=http://nord.net.ru/banner/cgi-bin/banner.cgi?"+append+"></a>"; document.write(str); </script> <br>
<br><p><a href="http://petrochat.pp.ru">В чат</a></center></BODY></HTML>
<?php
## functions:
function checkfull()
{
	global $b;
	global $gover;
	
	$gover = 1;
	for ($ii = 0; $ii <= 8; $ii++)
	{
		if ($b[$ii] == '')
		{
			$gover = 0;
			return;
		}
	}
}
####
function checkwin()
{
	global $b;
	global $gwin;
	$c=1;
	while ($c <= 2)
	{
		if ($c == 1)
			$t='o';
		else
			$t='x';
		if (
		# horizontal
		($b[0] == $t && $b[1] == $t && $b[2] == $t) || 
		($b[3] == $t && $b[4] == $t && $b[5] == $t) || 
		($b[6] == $t && $b[7] == $t && $b[8] == $t) || 
		# vertical
		($b[0] == $t && $b[3] == $t && $b[6] == $t) || 
		($b[1] == $t && $b[4] == $t && $b[7] == $t) ||
		($b[2] == $t && $b[5] == $t && $b[8] == $t) ||
		# diagonal
		($b[0] == $t && $b[4] == $t && $b[8] == $t) ||
		($b[2] == $t && $b[4] == $t && $b[6] == $t))
		{
			$gwin = strtoupper($t);
			return;
		}
		$c++;
	}
}

function compmove()
{
	global $cmv;
	global $b;
	for ($c = 0; $c <=1; $c++)
	{
		if ($c == 0)
			$t='o';
		else
			$t='x';
	
		if ($b[0] == $t && $b[1] == $t && $b[2] == '')
			$cmv = 2;
  	if ($b[0] == $t && $b[1] == '' && $b[2] == $t)
			$cmv = 1;		
		if ($b[0] == '' && $b[1] == $t && $b[2] == $t)
			$cmv = 0;
		if ($b[3] == $t && $b[4] == $t && $b[5] == '')
			$cmv = 5;
		if ($b[3] == $t && $b[4] == '' && $b[5] == $t)
			$cmv = 4;		
		if ($b[3] == '' && $b[4] == $t && $b[5] == $t)
			$cmv = 3;
			
		if ($b[6] == $t && $b[7] == $t && $b[8] == '')
			$cmv = 8;
		if ($b[6] == $t && $b[7] == '' && $b[8] == $t)
			$cmv = 7;		
		if ($b[6] == '' && $b[7] == $t && $b[8] == $t)
			$cmv = 6;
	
		if ($b[0] == $t && $b[3] == $t && $b[6] == '')
			$cmv = 6;
		if ($b[0] == $t && $b[3] == '' && $b[6] == $t)
			$cmv = 3;		
		if ($b[0] == '' && $b[3] == $t && $b[6] == $t)
			$cmv = 0;
			
		if ($b[1] == $t && $b[4] == $t && $b[7] == '')
			$cmv = 7;
		if ($b[1] == $t && $b[4] == '' && $b[7] == $t)
			$cmv = 4;		
		if ($b[1] == '' && $b[4] == $t && $b[7] == $t)
			$cmv = 1;
		if ($b[2] == $t && $b[5] == $t && $b[8] == '')
			$cmv = 8;
		if ($b[2] == $t && $b[5] == '' && $b[8] == $t)
			$cmv = 5;		
		if ($b[2] == '' && $b[5] == $t && $b[8] == $t)
			$cmv = 2;
	
	
		if ($b[0] == $t && $b[4] == $t && $b[8] == '')
			$cmv = 8;
		if ($b[0] == $t && $b[4] == '' && $b[8] == $t)
			$cmv = 4;		
		if ($b[0] == '' && $b[4] == $t && $b[8] == $t)
			$cmv = 0;
			
		if ($b[2] == $t && $b[4] == $t && $b[6] == '')
			$cmv = 6;
		if ($b[2] == $t && $b[4] == '' && $b[6] == $t)
			$cmv = 4;		
		if ($b[2] == '' && $b[4] == $t && $b[6] == $t)
			$cmv = 2;
		if ($cmv <> '')
			break;
	}
}
function comprand()
{
	global $b;
	global $cmv;
	srand ((double) microtime() * 1000000);
	while (! isset($cmv))
	{
		$test=rand(0, 8);
		if ($b[$test] == '')
			$cmv=$test;
	}	
}
?>