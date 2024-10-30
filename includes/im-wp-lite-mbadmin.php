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

class IMWPLiteMultiBlocksAdmin
{
	public function admin_menu() 
	{
		add_submenu_page(
			'options-general.php', 
			__('IM WP MultiBlocks Lite', 'im-wp-lite-multiblocks'), 
			__('IM WP MultiBlocks Lite', 'im-wp-lite-multiblocks'), 
			5,
			IM_WP_LITE_MB_FILE, 
			array($this, 'plugin_menu')
		);
	}
	
	public function plugin_menu()
	{
		$message = '';

		$settingsProvider = new IMWPLiteMBSettings();
		
		// Сохранение настроек
		if (isset($_POST['action']) && $_POST['action'] == 'update_im_wp_lite_mb_form'
			&& isset($_POST['im_wp_lite_mb']) && !empty($_POST['im_wp_lite_mb']))
		{
			$message = __('Настройки сохранены', 'im-wp-lite-multiblocks');
			
			$settingsProvider->save($_POST['im_wp_lite_mb']);
		}
		
		$this->im_wp_lite_mb_css();
		
		$this->im_wp_lite_mb_open_form($message);

		$this->im_wp_lite_mb_form_body_table($settingsProvider);

		$this->im_wp_lite_mb_close_form();
		
	}
	
	protected function im_wp_lite_mb_form_body_table($settingsProvider)
	{
		$echoHtml = '';
		$settings = $settingsProvider->get();	

		echo '<tr><th colspan="2" class="im-wp-lite-th-h3"><h3 class="im-wp-lite-h3">' 
				. __('Основные настройки', 'im-wp-lite-multiblocks') 
			. '</th></tr>'
		;

		// Вначале статьи
		echo $this->im_wp_lite_mb_echo_textarea_field(
			'text_pre', 
			__('Блок вначале статьи', 'im-wp-lite-multiblocks'),
			IMWPLiteMBSettings::getValue($settings, 'text_pre', '')
		);

		// Минимальный размер статьи
		echo $this->im_wp_lite_mb_echo_input_field(
			'text_pre_min', 
			htmlspecialchars(
				__('Блок вначале статьи - минимальный размер - (без HTML)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'text_pre_min', '')
		);

		// Блок внутри
		echo $this->im_wp_lite_mb_echo_textarea_field(
			'text_inner', 
			__('Блок в середине статьи (ближайший тег &lt;p/&gt;)', 'im-wp-lite-multiblocks'),
			IMWPLiteMBSettings::getValue($settings, 'text_inner', '')
		);

		// Минимальный размер статьи
		echo $this->im_wp_lite_mb_echo_input_field(
			'text_inner_min', 
			htmlspecialchars(
				__('Блок в середине статьи - минимальный размер - (без HTML)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'text_inner_min', '')
		);

		// Вначале статьи
		echo $this->im_wp_lite_mb_echo_textarea_field(
			'text_after', 
			__('Блок в конце статьи', 'im-wp-lite-multiblocks'),
			IMWPLiteMBSettings::getValue($settings, 'text_after', '')
		);

		// Минимальный размер статьи
		echo $this->im_wp_lite_mb_echo_input_field(
			'text_after_min', 
			htmlspecialchars(
				__('Блок в конце статьи - минимальный размер - (без HTML)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'text_after_min', '')
		);
		
		/////////////////////
		// Исключения
		/////////////////////
		
		echo '<tr><th colspan="2" class="im-wp-lite-th-h3"><h3 class="im-wp-lite-h3">' 
				. __('Настройка исключений', 'im-wp-lite-multiblocks') 
			. '</th></tr>'
		;
		
		// Исключить ID статей
		echo $this->im_wp_lite_mb_echo_input_field(
			'exclude_mat_id', 
			htmlspecialchars(
				__('Исключить ID статей (через запятую)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'exclude_mat_id', '')
		);

		// Исключить ID категорий
		echo $this->im_wp_lite_mb_echo_input_field(
			'exclude_cat_id', 
			htmlspecialchars(
				__('Исключить ID категорий (через запятую)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'exclude_cat_id', '')
		);

		// Исключить IP адреса
		echo $this->im_wp_lite_mb_echo_textarea_field(
			'exclude_ip', 
			htmlspecialchars(
				__('Исключить IP адреса (через запятую)', 'im-wp-lite-multiblocks'),
				ENT_NOQUOTES
			),
			IMWPLiteMBSettings::getValue($settings, 'exclude_ip', '')
		);
	
	}
	
	// Поле ввода
	protected function im_wp_lite_mb_echo_input_field($name, $desc = '', $curvalue = '', $class = '')
	{
		
		$result = '<tr>';
		
		$result .= '<th scope="row" style="width: 30%;">'
					. '<label>'
						. ($desc == '' ? $name : $desc)
					. '</label>'
				. '</th>'
		;
		
		$result .= '<td style="vertical-align: top; padding-top: 20px;">'
					. '<input type="text" class="' . $class . '" '
						. ' style="width:100%;" '
						. ' name="im_wp_lite_mb[' . $name . ']" '
						. ' value="' . $curvalue . '" '
					. '/>'
				. '</td>'
		;
		
		return $result . '</tr>';
	}	

	// Поле ввода
	protected function im_wp_lite_mb_echo_textarea_field($name, $desc = '', $curvalue = '', $class = '')
	{
		
		$result = '<tr>';
		
		$result .= '<th scope="row" style="width: 30%;">'
					. '<label>'
						. ($desc == '' ? $name : $desc)
					. '</label>'
				. '</th>'
		;
		
		$result .= '<td style="vertical-align: top; padding-top: 20px;">'
					. '<textarea cols="80" rows="5" '
						. ' style="width: 100%;" '
						. ' class="' . $class . '" '
						. ' name="im_wp_lite_mb[' . $name . ']" '
					. '>'
						. $curvalue
					. '</textarea>'
				. '</td>'
		;
		
		return $result . '</tr>';
	}	
	
	// Начало формы
	protected function im_wp_lite_mb_open_form($message) 
	{
		$echoHtml = '';
		
		if (isset($message) && !empty($message)) {
			$echoHtml .= '<div id="message" class="updated fade"><p>'
				. $message
				. '</p></div>'
			;
		}
		
		$echoHtml .=
			'<div id="dropmessage" class="updated" style="display:none;"></div>'
			. '<div class="wrap">'
				. '<h2 class="im-wp-lite-h2">' . __('IM WP MultiBlocks Lite - Настройки', 'im-wp-lite-multiblocks') . '</h2>'
				. '<p>'
					. '<span>'
						. __('Вставка в статьи рекламы и произвольных блоков', 'im-wp-lite-multiblocks')
					. '</span>' 
					. '<br>'
					. '<br>'
					. '<span class="im-wp-lite-info">'
						. __('Если вам нужна платная полная версия плагина, то обращайтесь.', 'im-wp-lite-multiblocks')
						. '<br/>'
						. __('(Номера абзацев, исключение рекламы под заголовками или картинками, а так же другие возможности).', 'im-wp-lite-multiblocks')
					. '</span>' 
					. '<br>'
					. '<br>'
					. '<span>'
						. '[ '
							. ' dev.imirochnik@gmail.com '
							. ' | <a href="http://ida-freewares.ru" target="_blank">Ida-Freewares.ru</a> '
							. ' | <a href="http://IM-Cloud.ru" target="_blank">IM-Cloud.ru</a> '
						. ']' 
					. '</span>' 
				. '</p>'
				. '<form action="" method="post">'
					. '<div class="postbox">'
						. '<div class="inside">'
							. '<table class="form-table">'
		;
		
		echo $echoHtml;
	}

	// Конец формы
	protected function im_wp_lite_mb_close_form() 
	{
		$echoHtml = '';
		
		$echoHtml .=
							'</table>'
						. '</div>'
					. '</div>'
					. '<p class="submit" style="text-align:center">'
						. '<input type="hidden" name="action" value="update_im_wp_lite_mb_form" />'
						. '<input type="submit" class="im-wp-lite-mb-btn" name="Submit" '
							. 'value="'
								. __('Сохранить настройки', 'im-wp-lite-multiblocks')
							. '" />'
					. '</p>'
				. '</form>'
			. '</div>'
			. '<div class="im-wp-lite-author">'
				. __('Игорь Мирочник &copy; IM WP MultiBlocks Lite') . ' ver. ' . IM_WP_LITE_VERSION
			. '</div>'
		;
		
		echo $echoHtml;
	}

	// Стили формы
	protected function im_wp_lite_mb_css()
	{
		echo <<<HTML
		<style type="text/css">
.im-wp-lite-mb-btn:hover {
    background: #80af45;
    cursor: pointer;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #96c161), color-stop(100%, #80af45));
    background-image: -webkit-linear-gradient(top, #96c161, #80af45);
    background-image: -moz-linear-gradient(top, #96c161, #80af45);
    background-image: -o-linear-gradient(top, #96c161, #80af45);
    background-image: linear-gradient(top, #96c161, #80af45);
    -webkit-transition: box-shadow 0.05s ease-in-out;
    -moz-transition: box-shadow 0.05s ease-in-out;
    -o-transition: box-shadow 0.05s ease-in-out;
    transition: box-shadow 0.05s ease-in-out;
}
.im-wp-lite-mb-btn {
    margin-bottom: 5px;
    border: 1px solid #6d963b;
    -webkit-box-shadow: inset 0 1px 2px #a2c873;
    -moz-box-shadow: inset 0 1px 2px #a2c873;
    box-shadow: inset 0 1px 2px #a2c873;
    background: #82b346;
    background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
    background-size: 100%;
    background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #96c161), color-stop(100%, #82b346));
    background-image: -webkit-linear-gradient(top, #96c161, #82b346);
    background-image: -moz-linear-gradient(top, #96c161, #82b346);
    background-image: -o-linear-gradient(top, #96c161, #82b346);
    background-image: linear-gradient(top, #96c161, #82b346);
	color: #fff;
	border-radius: 2px;
    font-size: 11px;
    font-weight: 600;
    text-shadow: 0 -1px #6f6f6f;
    padding: 10px;
}
.im-wp-lite-info
{
	font-style: italic;
}

.im-wp-lite-h2,
.postbox .inside h2.im-wp-lite-h2,
.wrap [class$=icon32]+h2.im-wp-lite-h2,
.wrap>h2:first-child.im-wp-lite-h2
{
	border-bottom: 2px solid #5D6A75;
}

.im-wp-lite-h3
{
	color: #32497D;
	font-family: Georgia;
}

.im-wp-lite-th-h3,
.form-table th.im-wp-lite-th-h3
{
	padding: 0px;
	border-bottom: 2px solid #76C0FF;
}

.im-wp-lite-author
{
	min-width: 255px;
	border: 1px solid #e5e5e5;
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
	box-shadow: 0 1px 1px rgba(0,0,0,.04);
	background: #fff;
	margin: 10px 20px 0 2px;
	padding: 20px;
	color: #38475A;
	font-size: 13px;
}

		</style>
HTML;
	}
}

$IMWPLiteMultiBlocksAdminSingle = new IMWPLiteMultiBlocksAdmin();

add_action('admin_menu', array($IMWPLiteMultiBlocksAdminSingle, 'admin_menu'));
