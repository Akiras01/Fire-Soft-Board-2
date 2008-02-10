<?php
/*
** +---------------------------------------------------+
** | Name :		~/programms/diff.php
** | Begin :	07/11/2006
** | Last :		07/11/2006
** | User :		Genova
** | Project :	Fire-Soft-Board 2 - Copyright FSB group
** | License :	GPL v2.0
** +---------------------------------------------------+
*/

die('Pour pouvoir utiliser ce fichier veuillez decommenter cette ligne. <b>Cefichier est une faille potentielle de s�curit�</b>, ne l\'utilisez qu\'en local, ou si vous �tes certain de ce que vous faites');

/*
** Ce fichier permet de comparer deux fichiers
*/

// On supprime toutes les variables cr�es par la directive register_globals
// On stripslashes() toutes les variables GPC pour la compatibilit� DBAL
$gpc = array('_GET', '_POST', '_COOKIE');
$magic_quote = (get_magic_quotes_gpc()) ? TRUE : FALSE;
$register_globals = (ini_get('register_globals')) ? TRUE : FALSE;

if ($register_globals || $magic_quote)
{
	foreach ($gpc AS $value)
	{
		if ($register_globals)
		{
			foreach ($$value AS $k => $v)
			{
				unset($$k);
			}
		}

		if ($magic_quote)
		{
			$$value = array_map('stripslashes', $$value);
		}
	}
}

include('../main/class/class_fsb_model.php');
include('../main/class/class_diff.php');

$diff1 = (isset($_POST['diff1'])) ? $_POST['diff1'] : '';
$diff2 = (isset($_POST['diff2'])) ? $_POST['diff2'] : '';

echo '<form action="" method="post">';
echo '<textarea name="diff1" rows="20" cols="60">' . $diff1 . '</textarea> <textarea name="diff2" rows="20" cols="60">' . $diff2 . '</textarea>';
echo '<br /><input type="checkbox" value="1" name="wrap" checked="checked" /> Wrap automatique &nbsp; &nbsp; &nbsp; ';
echo '<input type="submit" name="submit" value="Comparer" /></form>';

if (isset($_POST['submit']))
{
	echo '<br /><br />';

	$diff = new Diff();
	$diff->load_content($diff1, $diff2);
	$diff->output($_POST['wrap']);

	$diff1 = htmlspecialchars($diff1);
	$diff2 = htmlspecialchars($diff2);
}
?>