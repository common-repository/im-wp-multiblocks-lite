<?php

/*
	Plugin Name: IM WP MultiBlocks Lite
	Plugin URI: http://IM-Cloud.ru/
	Description: Lets you insert your ad-code (like AdSense).
	Version: 1.0.2
	Author: Igor Mirochnik
	Author URI: http://Ida-Freewares.ru/
*/

/*
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

	(Это свободная программа: вы можете перераспространять ее и/или изменять
	ее на условиях Стандартной общественной лицензии GNU в том виде, в каком
	она была опубликована Фондом свободного программного обеспечения; либо
	версии 3 лицензии, либо (по вашему выбору) любой более поздней версии.

	Эта программа распространяется в надежде, что она будет полезной,
	но БЕЗО ВСЯКИХ ГАРАНТИЙ; даже без неявной гарантии ТОВАРНОГО ВИДА
	или ПРИГОДНОСТИ ДЛЯ ОПРЕДЕЛЕННЫХ ЦЕЛЕЙ. Подробнее см. в Стандартной
	общественной лицензии GNU.

	Вы должны были получить копию Стандартной общественной лицензии GNU
	вместе с этой программой. Если это не так, см.
	<http://www.gnu.org/licenses/>.)
*/

// version
define('IM_WP_LITE_VERSION', '1.0.2');

define('IM_WP_LITE_MB_DIR', plugin_dir_path(__FILE__));
define('IM_WP_LITE_MB_URL', plugin_dir_url(__FILE__));
define('IM_WP_LITE_MB_FILE', __FILE__);

function im_wp_lite_mb_load() {
	// подключаем файлы администратора, только если он авторизован
    if (is_admin()) { 
        require_once(IM_WP_LITE_MB_DIR . 'includes/im-wp-lite-mbadmin.php');
    }

    require_once(IM_WP_LITE_MB_DIR . 'includes/im-wp-lite-mbcore.php');

}

function im_wp_lite_mb_plugins_loaded()
{
	// Подключение локализации
	if (function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain(
			'domain', 
			false, 
			basename( dirname( __FILE__ ) ) . '/lang/'
		);
	}
}

// Add actions
add_action('plugins_loaded', 'im_wp_lite_mb_plugins_loaded');

register_activation_hook(__FILE__, 'im_wp_lite_mb_activation');

function im_wp_lite_mb_activation() {
    // действие при активации
    update_option('im-wp-lite-mb-active', '1');
    // регистрируем действие при удалении
    register_uninstall_hook(__FILE__, 'im_wp_lite_mb_uninstall');
}

function im_wp_lite_mb_uninstall() {
    update_option('im-wp-lite-mb-active', '0');
}


im_wp_lite_mb_load();
