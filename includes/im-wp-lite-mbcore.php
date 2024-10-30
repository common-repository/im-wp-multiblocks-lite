<?php
/*
	Inner Plugin Name: IM WP MultiBlocks Lite
	Inner Plugin URI: http://IM-Cloud.ru/
	Inner Description: Lets you insert your ad-code (like AdSense).
	Inner Version: 1.0.2
	Inner Author: Igor Mirochnik
	Inner Author URI: http://Ida-Freewares.ru/
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

require_once (IM_WP_LITE_MB_DIR . 'includes/im-wp-lite-mbsettings.class.php');
require_once (IM_WP_LITE_MB_DIR . 'includes/im-wp-lite-mbparser.class.php');

class IMWPLiteMultiBlocksCore
{
	/** регистрация фильтров и действий * */
	public function init() 
	{
	    add_action('init', array($this, 'init'));
	    add_filter('the_content', array($this, 'the_content'));
	}

	/** модифицировать текст * */
	public function the_content($content) 
	{
	    global $post;

		$is_active = (int)get_option('im-wp-lite-mb-active', '0');

	    if (is_single() && is_singular( ['post', 'page'] ) && $is_active == 1) 
	    {
	    	$settingsProvider = new IMWPLiteMBSettings();
	    	$parser = new IMWPLiteMBParser($settingsProvider->get());

	    	// Список категорий
	    	$categoryList = array();
	    	foreach(get_the_category($post->ID) as $category) { 
	    		$categoryList[] = $category->cat_ID;
			} 
	    	
	    	$content = $parser->getValue(
	    		array(
	    			'text' => $content,
	    			'id' => $post->ID,
	    			'category' => join(',', $categoryList)
	    		)
	    	);
		}

	    return $content;
	}
	
}

$IMWPLiteMultiBlocksCoreSingle = new IMWPLiteMultiBlocksCore();

add_action('plugins_loaded', array($IMWPLiteMultiBlocksCoreSingle, 'init'));
