<?php

/**
 * Максимально "полегшений" варіант моделі фреймворку.
 */
class SimpleModel extends stdClass
{
    /**
     * @var int Час життя кешів (у секундах)
     */
    const CACHE_TTL = 300;

    /**
     * @var array - contains a dynamic instances of Environment class
     */
    protected static $_models = array();

    /**
     * Метод для оперування нестатичними методами та полями класу. Ідея "стибрена"
     * із коду Yii
     *
     * Приклад використання
     * @code
     *   SimpleModel::model()->someDynamicMethod();
     *   // or
     *   SimpleModel::model()->someDynamicProperties;
     * @endcode
     */
    public static function model($className = __CLASS__)
    {
        if (isset(self::$_models[$className]))
        {
            return self::$_models[$className];
        }

        return self::$_models[$className] = new $className(null);
    }
	
	protected  function _buildPath($userId){
		return implode("/",  str_split( sprintf("%010d", $userId), 2 ) );
	}
}