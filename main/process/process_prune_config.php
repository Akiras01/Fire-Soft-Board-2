<?php
/*
** +---------------------------------------------------+
** | Name :		~/main/process/process_prune_config.php
** | Begin :	11/07/2007
** | Last :		10/08/2007
** | User :		Genova
** | Project :	Fire-Soft-Board 2 - Copyright FSB group
** | License :	GPL v2.0
** +---------------------------------------------------+
*/

$GLOBALS['use_register_shutdown'] = FALSE;

/*
** Recalcul des donn�es en cache dans la configuration
*/
function prune_config()
{
	$list_config = array('total_posts', 'total_topics', 'total_users', 'last_user');
	foreach ($list_config AS $value)
	{
		if ($value == 'last_user')
		{
			// Pour le calcul du dernier membre on doit calcul son nickname et son ID
			$sql = 'SELECT u_id, u_nickname, u_color
					FROM ' . SQL_PREFIX . 'users
					ORDER BY u_joined DESC
					LIMIT 1';
			$data = Fsb::$db->request($sql);

			Fsb::$cfg->update('last_user_id', $data['u_id'], FALSE);
			Fsb::$cfg->update('last_user_login', $data['u_nickname'], FALSE);
			Fsb::$cfg->update('last_user_color', $data['u_color'], FALSE);
		}
		else
		{
			$sql = 'SELECT COUNT(*) AS total
					FROM ' . SQL_PREFIX . substr($value, 6);
			$total = Fsb::$db->get($sql, 'total');

			// Pour total_users on supprime 1 pour l'invit�
			if ($value == 'total_users')
			{
				$total--;
			}

			Fsb::$cfg->update($value, $total, FALSE);
		}
	}
	Fsb::$cfg->destroy_cache();
}
/* EOF */