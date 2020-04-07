<?php
#注册插件
RegisterPlugin("Huceo_formula","ActivePlugin_Huceo_formula");

function ActivePlugin_Huceo_formula() {
	Add_Filter_Plugin('Filter_Plugin_Edit_Response2','Huceo_formula_Filter_Plugin_Edit_Begin');
}

function Huceo_formula_Filter_Plugin_Edit_Begin() {
	global $zbp;

	echo '<script type="text/javascript" charset="utf-8" src="'. $zbp->host .'zb_users/plugin/Huceo_formula/kityformula-plugin/addKityFormulaDialog.js"></script>
<script type="text/javascript" charset="utf-8" src="'. $zbp->host .'zb_users/plugin/Huceo_formula/kityformula-plugin/getKfContent.js"></script>
<script type="text/javascript" charset="utf-8" src="'. $zbp->host .'zb_users/plugin/Huceo_formula/kityformula-plugin/defaultFilterFix.js"></script>
<script type="text/javascript" charset="utf-8" src="'. $zbp->host .'zb_users/plugin/Huceo_formula/common.js"></script>';
}

 


function InstallPlugin_Huceo_formula() {}


function UninstallPlugin_Huceo_formula() {
	global $zbp;

	$zbp->DelConfig('Huceo_formula');

}