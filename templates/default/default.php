<?php
	// Created by Mark Scholten
	// This file is called default.php, this is the default layout.
	fix_is_included($index);
	
	//algemene layout
	$content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	    <head>
	        <title>'.$lang->translate(24).' - '.get_value_session('from_db','handelsnaam');
			if(!empty($cp_version) && isset($cp_version) && $cp_version !== FALSE){
				$content .= '- V'.$cp_version;
			}
			$content .= '</title> 
	        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	        <link href="templates/default/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
<script language="javascript" src="jquery-1.3.2.js"></script>
<script language="JavaScript">
function onChangeType(id) 
{
	var type = $(\'#type_\'+id+" option:selected").val();
	
	if(type.toUpperCase() == "MX") {
		$(\'#prio_\'+id).show();
		$(\'#prio_\'+id).focus();
	}
	else
	{
		$(\'#prio_\'+id).hide();	
	}
}

function confirm_text(text, url){
	
	if(confirm(text) == true){
		
		window.location = url;
	}
	else{
		
		window.location = "#";
	}
}
function deleteRecord(record_id, rownumber) 
{	
	var params = "&record_id=" + record_id;
	$.ajax({
		type:"POST",
		url: "delete.php",
		data: params,
		success: function(result, args) 
		{ 	
			alert(result);	
			$("#row_"+rownumber).remove();
			var rowcount = $(\'#tableid tr\').length-2;
			var value = $("#tableid tr:eq("+rowcount+")").attr(\'id\');
			var lastrownumber = parseInt(value.substr(4, value.length));
			$(\'#addrow_\'+lastrownumber).html("<input type=\\"button\\" onclick=\\"javascript:addnewrow("+lastrownumber+")\\" value=\\"&nbsp;+&nbsp;\\"/>");
			$("body").css("cursor", "auto");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) 
		{
			$("body").css(\'cursor\', \'auto\');
		},
		dataType: "text"
	});	
}


function validate() 
{
	var rows = $(\'#tableid tr\');
	
	for(var i=0; i < rows.length - 1; i++)
	{
		var id = $(rows[i]).attr("id");
		
		var rownumber = parseInt(id.substr(4, id.length));
		var name =  jQuery.trim($(\'#name_\'+rownumber).val());
		var ttl = $(\'#ttl_\'+rownumber).val();
		var type = $(\'#type_\'+rownumber+\' option:selected\').val();
		var prio = $(\'#prio_\'+rownumber).val();
		
		
		$(\'#name_\'+rownumber).removeClass(\'error\');
		$(\'#ttl_\'+rownumber).removeClass(\'error\');
		$(\'#prio_\'+rownumber).removeClass(\'error\');
		$(\'#content_\'+rownumber).removeClass(\'error\');
		
		
		if(/^.*\\.$/.test(name))
		{
			$(\'#name_\'+rownumber).addClass(\'error\');
			$(\'#name_\'+rownumber).focus();
			return false;
		}
		
		if(type.toUpperCase() == "MX")
		{
			var prio = $(\'#prio_\'+rownumber).val();
			prio = jQuery.trim(prio);
			if(prio == \'\' || isNaN(prio))
			{
				$(\'#prio_\'+rownumber).addClass(\'error\');
				$(\'#prio_\'+rownumber).focus();
				return false;
			}
			
			if(!isUnique(rows, prio, rownumber))
			{
				$(\'#prio_\'+rownumber).addClass(\'error\');
				$(\'#prio_\'+rownumber).focus();
				return false;
			}
		}
		
 	} 
 	return true;
}


function isUnique(rows, value, rownr) 
{
	
	for(var i=0; i < rows.length-1; i++)
	{
		var id = $(rows[i]).attr("id");
		var rownumber = parseInt(id.substr(4, id.length));
		var prio = $(\'#prio_\'+rownumber).val();
			
		if(rownr != rownumber && value == prio)
		{
			return false;
		}
	}
	return true;
}


function confirmation() {
	var answer = confirm("'.$lang->translate(716).'")
	if (answer){
		return true;
	}
	return false;
}



function removerow(rownumber) 
{	
	if($(\'#tableid tr\').length < 3)
		return;	
	
	if($("#row_"+rownumber) != undefined)
	{
		// vraag om bevestiging
		if(!confirmation()) 
		{
			return false;
		}
		
		var record_id = $("#id_"+rownumber).val();
		
		if(record_id > 0) 
		{
			// bestaat in database
			//deleteRecord(record_id, rownumber);
			$("#row_"+rownumber).remove();
			var rowcount = $(\'#tableid tr\').length-1;
			var value = $("#tableid tr:eq("+rowcount+")").attr(\'id\');
			var lastrownumber = parseInt(value.substr(4, value.length));
			$(\'#addrow_\'+rownumber).html("<input type=\\"button\\" onclick=\\"javascript:addnewrow("+rownumber+")\\" value=\\"&nbsp;+&nbsp;\\"/>");
		}
		else 
		{
			//  bestaat nog niet in database
			$("#row_"+rownumber).remove();
			var rowcount = $(\'#tableid tr\').length-1;
			var value = $("#tableid tr:eq("+rowcount+")").attr(\'id\');
			var lastrownumber = parseInt(value.substr(4, value.length));
			$(\'#addrow_\'+lastrownumber).html("<input type=\\"button\\" onclick=\\"javascript:addnewrow("+lastrownumber+")\\" value=\\"&nbsp;+&nbsp;\\"/>");
		}
	}
}

function addnewrow(rownumber)
{
	$("#addrow_"+rownumber).html("");
	var newrownumber = rownumber+1;
	var newrow = "<tr id=row_"+newrownumber+">";
	newrow += "<td><input name=\\"id[]\\" id=\\"id_"+newrownumber+"\\" type=\\"hidden\\" value=\\"0\\"/><input id=\\"name_"+newrownumber+"\\" name=\\"name[]\\"/></td>";
	newrow += "<td><input id=\\"ttl_"+newrownumber+"\\" name=\\"ttl[]\\" value=\\"900\\"/></td>";
	newrow += "<td><select id=\\"type_"+newrownumber+"\\" name=\\"type[]\\" onchange=\\"onChangeType("+newrownumber+")\\"/></td>";
	newrow += "<td><input id=\\"prio_"+newrownumber+"\\" name=\\"prio[]\\" style=\\"display:none\\"/></td>";
	newrow += "<td><input id=\\"content_"+newrownumber+"\\" name=\\"content[]\\"/></td>";
	newrow += "<td id=\\"removerow_"+newrownumber+"\\"><input type=\\"button\\" onclick=\\"javascript:removerow("+newrownumber+")\\" value=\\"&nbsp;-&nbsp;\\"/></td>";
	newrow += "<td id=\\"addrow_"+newrownumber+"\\"><input type=\\"button\\" onclick=\\"javascript:addnewrow("+newrownumber+")\\" value=\\"&nbsp;+&nbsp;\\"/></td>";
	newrow += "</tr>";
	$(\'#row_\'+rownumber).after(newrow);
	
	var id = $(\'#tableid tr:eq(0)\').attr(\'id\');
	id = parseInt(id.substr(4, id.length));
	$(\'#type_\'+id + \' option\').each(function() 	
	{	
		 $(\'#type_\'+newrownumber).append(new Option($(this).text()));
	});
}		
</script>



	    </head>

	    <body>
			<div id="header">
	        	<div id="container">
	                <div id="login">
	                   <p >'.$lang->translate(21).'</p>  
					   '.get_value_session('from_db','username').'<br />
					   '.get_value_session('from_db','email').'
	                </div> 
	                <div id="slogan">
					<h1> '.get_value_session('from_db','handelsnaam').' </h1>
	                    <p> <a href="'.get_value_session('from_db','home_page').'" target="_blank">'.$lang->translate(20).'</a> </p>
	                </div> 
	                 <div id="logo">
	                    <a href="index.php"><img src="templates/default/img/default/logo.jpg" alt=""/></a>
	                </div> 
	             </div>
	        </div>
	        <div id="menu_top">
	    	    <p>     </p>
	        </div> 
			<div id="wrapper_main">
	            <div id="wrapper_left">    
	            	<div id="wrapper_mainmenu">';
		foreach($menu as $hoofdmenu => $hmitem){
			$content .= '<div class="item_parent">'.$hoofdmenu.'</div>';
			foreach($hmitem as $submenu => $smitem){
				if(isset($smitem['extlink']) && !empty($smitem['extlink'])){
					$content .= '<div class="item_child"><a href="'.$smitem['extlink'].'">'.$submenu.'</a></div>';
				}else{
					$content .= '<div class="item_child"><a href="?lang='.lang_get_value_defaultlang().''.$smitem.'">'.$submenu.'</a></div>';
				}
			}
		}
		$content .= ' </div>	
	                
	                <div id="bottom"> 
	                </div>  			
	             </div>
	             <div id="wrapper_center">    
	                <div id="path">
	                	<div class="wrapper">
	                    </div>
	                </div>
	                <div id="content"> '.$html.'<br /></div>
	    		</div>          
			</div>            
	    </body> 
</html>';
?>
		