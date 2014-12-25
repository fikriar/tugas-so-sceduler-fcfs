<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CPU Scheduler</title>
</head>
<style>
body{
	font:11px 'Open Sans', Arial, Calibri;
}
button{
	font:12px 'Open Sans', Arial, Calibri;
	height:27px;
}
#border{
	width:90%;
	margin:30px auto;
}
#garis-luar{
	display:inline-table;
	min-height:200px;
	width:100%;
}
#inputan-scd{
	overflow:auto;
	float:left;
	width:49%;
	min-height:320px;
	border:1px solid #000;
}
#tabel-scd{
	overflow:auto;
	color:#000;
	float:right;
	width:49%;
	min-height:320px;
	border:1px solid #000;
}
input.prog{
	background:#EEE;
	border:#FFF;
	width:50px;
}
table{
	width:60%;
	border-collapse:collapse;
	margin:10px;
}
table,td,th{
	border:0px solid #666;
}
td,th{
	padding:7px;
}
input{
	border:1px solid #C4C6AE;
	font:11px 'Open Sans', 'Myriad Pro', Arial, Calibri;
	height:20px;
	padding-left:7px;
}
input.ubah{
	width:40%;
}
#diagram-scd{
	padding:15px;
	padding-bottom:40px;
	background:#ECE6D7;
	margin-top:10px;
	float:left;
	min-height:80px;
	width:97.6%;
}
#tableP td,#tableP th,#table tr{
	padding:2px;
}
#gantt2{
	padding-top:15px;
	font:12px 'Myriad Pro', 'Open Sans', Calibri, Arial;
	text-align:center;
	float:left;
	background:#0F90F9; 
	box-shadow:2px 2px 5px #666; 
	height:40px;
	border:1px solid #000;
}
#agkjumlah{
	margin-right:-5px;
	margin-top:41px;
	float:right;
}
</style>
<body>
<div id="border">
<div id="garis-luar">
	<div id="inputan-scd">
        <table>
        <tr><td colspan="2" align="right">Jumlah Tabel</td><td align="right">
        <form id="loop" action="scheduler.php" method="post">
        <input type="text" name="nbaris" class="ubah">
        <button  type="submit" name="submit">Ubah</button>
        </form>
        </td></tr>
        <tr></tr>
        <tr><th>Proses</th><th>Arrival Time (ms)</th><th>Burst Time (ms)</th></tr>
        <?php 
		$n=isset($_POST['nbaris'])?trim($_POST['nbaris']):4;
		if($n==""){ $n=4; }
		$i=1;
		while($i<=$n){?>
        <tr><td>P<?php echo $i;?></td>
        <?php if($i==1){?>
        	<td><input type="text" name="arrival<?php echo $i;?>" id="arrival<?php echo $i;?>" value="0" disabled="disabled"/></td>
        <?php }else{?>
        	<td><input type="text" name="arrival<?php echo $i;?>" id="arrival<?php echo $i;?>" maxlength="3" /></td>
        <?php }?>
        <td><input type="text" name="burst<?php echo $i;?>" id="burst<?php echo $i;?>" maxlength="3" /></td></tr>
        <?php $i++; }?>
        <tr>
        <td colspan="3" align="right">
        <button type="submit" name="proses" onclick="tset();">Proses</button>
        <button type="reset" name="reset">Reset</button>
        </td></tr>
        </table>
    </div>
    <div id="tabel-scd">
    <div id="table-proses">
     <table id="tableP" style="width:240px;">
        <tr><th>Proses</th><th>Arrival Time</th><th>Burst Time</th></tr>
        <?php 
		$i=1;
		while ($i<=$n){?>
        <tr>
        <td><center><input type="text" class="prog" id="p<?php echo $i;?>" disabled="disabled" /></center></td>
        <td><center><input type="text" class="prog" id="arvT<?php echo $i;?>" disabled="disabled" /></center></td>
        <td><center><input type="text" class="prog" id="bstT<?php echo $i;?>"disabled="disabled" /></center></td>
        </tr>
        <?php $i++; }?>
     </table>
     <table>
        <tr><td colspan="3"><b>First Come First Served</b></td></tr>
        <tr><td colspan="2">Turn Around Time</td>
        <td><input type="text" name="TurnArTime1" id="TurnArTime1" disabled="disabled"/></td></tr>
        <tr><td colspan="2">Waiting Time</td>
        <td><input type="text" name="WaitTime1" id="WaitTime1" disabled="disabled"/></td></tr>
        <tr><td colspan="2">Response Time</td>
        <td><input type="text" name="RespTime1" id="RespTime1" disabled="disabled"/></td></tr>
        </table>
    </div>
    </div>
    <div id="diagram-scd" style="background:none;"></div>
</div>
</div>
</body>
<script type="text/javascript">
function tset(){
	var n = <?php echo $n; ?>;
	var arvT = new Array(n);
	var bstT = new Array(n);
	var juml = new Array(n);
	var urutan = new Array(n);
	for(i=0; i<n; i++){
		arvT[i] = parseInt(document.getElementById('arrival'+(i+1)).value);
		bstT[i] = parseInt(document.getElementById('burst'+(i+1)).value);
		urutan[i] = i+1;
	}
	var temp;
	for(i=0; i<n; i++){
		for(j=0; j<n-1; j++){
			if(arvT[j]>arvT[j+1]){
				//Urutin dari yang terkecil Arivalnya
				temp = arvT[j];
				arvT[j] = arvT[j+1];
				arvT[j+1] = temp;
				//
				temp = bstT[j];
				bstT[j] = bstT[j+1];
				bstT[j+1] = temp;
				//
				temp = urutan[j];
				urutan[j] = urutan[j+1];
				urutan[j+1] = temp;
			}
		}
	}
	var batas = 0;
	for(i=0; i<n; i++){
		batas+=bstT[i];
		juml[i]=batas;
		if(arvT[i+1]>batas){
		alert('Arival Harus lebih kecil dari jumlah Burst Time sebelumnya');
		}
	}
	var jmlburst=0;
	for(i=0; i<n; i++){
		jmlburst = jmlburst + juml[i];
	}
	
	//Algoritma First Come First Serve
	for(i=0; i<n; i++){
		document.getElementById('p'+(i+1)).value ='P'+urutan[i]; 
		document.getElementById('arvT'+(i+1)).value =arvT[i]+' ms';
		document.getElementById('bstT'+(i+1)).value =bstT[i]+' ms';  
	}
	
	var jmlWt =arvT[0];
	for(i=0; i<n-1; i++){
		jmlWt+=juml[i]-arvT[i+1];
	}
	var TAfcf =jmlburst/n;
	var WTfcf =jmlWt/n;
	var RTfcf = WTfcf;
	document.getElementById('TurnArTime1').value = Math.round(TAfcf*1000)/1000; 
	document.getElementById('WaitTime1').value = Math.round(WTfcf*1000)/1000;
	document.getElementById('RespTime1').value = Math.round(RTfcf*1000)/1000;
	
	var lebar =0;
	document.getElementById('diagram-scd').innerHTML='<p><h3>Diagram Gantt - First Come First Served</h3></p>';
	for(i=0; i<n; i++){
		lebar = bstT[i]/batas*97;
		if(i==0){
			document.getElementById('diagram-scd').innerHTML+='<div id="gantt2" style="width:'+lebar+'%;"><a>P'+urutan[i]+'</a><div id="agkjumlah" style="float:left;">'+arvT[0]+'</div><div id="agkjumlah">'+juml[i]+'</div></div>';
		}else{
			document.getElementById('diagram-scd').innerHTML+='<div id="gantt2" style="width:'+lebar+'%;margin-left:-1px;"><a>P'+urutan[i]+'</a><div id="agkjumlah">'+juml[i]+'</div></div>';
		}
	}
}
</script>


</html>
