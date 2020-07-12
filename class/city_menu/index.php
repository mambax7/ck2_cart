<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Chained comboboxes demo</title>
<script language="JavaScript" type="text/javascript" src="jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="jquery.chainedSelects.js"></script>
<script language="JavaScript" type="text/javascript">
$(function()
{
	$('#city').chainSelect('#town','combobox.php',
	{ 
		before:function (target)
		{ 
			$("#loading").css("display","block");
			$(target).css("display","none");
		},
		after:function (target)
		{ 
			$("#loading").css("display","none");
			$(target).css("display","inline");
		}
	});

});
</script>

</head>
<body>



<form name="formname" method="post" action="">

	<?php echo mk_city_opt();?>

</form>
<?php
function mk_city_opt($city=""){
	$city_array=array("台北市","基隆市","台北縣","宜蘭縣","新竹市","新竹縣","桃園縣","苗栗縣","台中市","台中縣","彰化縣","南投縣","嘉義市","嘉義縣","雲林縣","台南市","台南縣","高雄市","高雄縣","屏東縣","台東縣","花蓮縣","連江縣","澎湖縣","金門縣");
  $option="
  <div style='position:absolute;top:0px;right:0px;background:#ff0000;color:#fff;font-size:14px;font-familly:Arial;padding:2px;display:non'>載入中 ...</div>
	<select id='city' name='city'><option value=''>縣市</option>";
	foreach($city_array as $name){
	  $selected=($city==$name)?"selected":"";
		$option.="<option value='$name' $selected>$name</option>";
	}
	$option.="</select>
	<select name='town' id='town' style='display:none'></select>";
	return $option;
}
?>
