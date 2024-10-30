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

//////////////////////////////////////////
//////////////////////////////////////////
// Класс замены и парсинга
//////////////////////////////////////////
//////////////////////////////////////////
class IMWPLiteMBParser
{
	protected $settings;
	
	//////////////////////////////////////////
	// Получение настройки
	//////////////////////////////////////////
	protected function getSetting($name, $default = '')
	{
		if (isset($this->settings) && is_array($this->settings))
		{
			if (isset($this->settings[$name]) && !empty($this->settings[$name]))
			{
				return $this->settings[$name];
			}
		}
		
		return $default;
	}
	
	//////////////////////////////////////////
	// Конструктор
	//////////////////////////////////////////
	public function __construct($settings)
	{
		if (isset($settings) && is_array($settings)) {
			$this->settings = $settings;
		}
		else {
			$this->settings = array();
		}
	}
	
	//////////////////////////////////////////
	// Получаем значение
	//////////////////////////////////////////
	public function getValue($row)
	{
		$result = $row['text'];
		$resultSrcLen = mb_strlen(strip_tags($result));
		
		// Исключение
		if ($this->ignore($row['id'], $row['category'])) {
			return $result;
		}
		
		////////////////////
		// Блок ПРЕ
		////////////////////
		if ($resultSrcLen > (int)$this->getSetting('text_pre_min', 0)) {
			$result = trim($this->getSetting('text_pre', '')) . $result;
		}

		////////////////////
		// Блок ПОСЛЕ
		////////////////////
		if ($resultSrcLen > (int)$this->getSetting('text_after_min', 0)) {
			$result = $result . trim($this->getSetting('text_after', ''));
		}
		
		////////////////////
		// Блок в середине
		////////////////////
		if ($resultSrcLen > (int)$this->getSetting('text_inner_min', 0)) {
			$result = $this->insertInner($result, $this->getSetting('text_inner', ''));
		}
		
		return $result;
	}

	//////////////////////////////////////////
	// Вставка в середину
	//////////////////////////////////////////
	protected function insertInner(&$result,  &$addContent)
	{
		// Берем середину
		$middle_len = (int)( mb_strlen($result) / 2);
		// Часть до середины
		$before = mb_substr($result, 0, $middle_len);
		// Часть после середины
		$after = mb_substr($result, $middle_len);
		// Разбиваем значения на тексты с окончанием в </p>
		$after = explode('</p>', $after);
		// Сохраняем первую часть текста
		$savefirst_part = $after[0];
		// Убираем начало
		array_splice($after, 0, 1);
		// Теперь соединяем с рекламой
		$after = $savefirst_part . '</p>' . $addContent . implode('</p>', $after);
		// Возвращаем результат
		return $before . $after;
	}

	//////////////////////////////////////////
	// Проверка, что модуль нужно проверять
	//////////////////////////////////////////
    protected function ignore($id, $catId)
    {
        $ip = $this->getClientIp();
        $ignore = $this->exclude('exclude_ip', $ip);
        if ($ignore) {
            return $ignore;
        }

        $ignore = $this->exclude('exclude_mat_id', $id);
        if ($ignore) {
            return $ignore;
        }

		if (isset($catId) && !!trim($catId))
		{
			$catIdArray = explode(',', $catId);
			
			foreach($catIdArray as $item)
			{
		        $ignore = $this->exclude('exclude_cat_id', trim($item));
		        if ($ignore) {
		            return $ignore;
		        }
			}
		}


        return $ignore;
    }

	//////////////////////////////////////////
	// Функция проверки на исключение
	//////////////////////////////////////////
    protected function exclude($paramName, $value)
    {
        $excludeArticlesIds = $this->settings[$paramName];
        $excludeArticlesIdsArray = explode(',', $excludeArticlesIds);
        if (empty($excludeArticlesIdsArray)) {
            return 0;
        }
        if (!$value) {
            return 0;
        }
        
        foreach($excludeArticlesIdsArray as $key => $item)
        {
			$excludeArticlesIdsArray[$key] = trim($item);
		}
        
        if (in_array($value, $excludeArticlesIdsArray, false)) {
            return 1;
        }
        return 0;
    }

	//////////////////////////////////////////
	// Получение IP клиента 
	//////////////////////////////////////////
    protected function getClientIp()
    {
        global $_SERVER;
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];

        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }

        return $realip;
    }

}