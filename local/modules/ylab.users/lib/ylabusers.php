<?php


namespace Ylab\Users;


use Bitrix\Main\Entity;

/**
 * Class YlabUsersTable
 * @package Ylab\Users
 */
class YlabUsersTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_ylab_users';
    }

    /**
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('NAME', [
                'required' => true
            ]),
            new Entity\StringField('CITY', [
                'required' => true
            ]),
            new Entity\StringField('PHONE', [
                'required' => true
            ]),
            new Entity\DateField('DATE_BIRTH', [
                'required' => true
            ]),
        ];
    }
}