<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Ylab\Users\YlabUsersTable;

/**
 * Class UsersListComponent
 */
class UsersListComponent extends CBitrixComponent
{
    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        try {
            Loader::includeModule("ylab.users");
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        try {
            $this->arResult = $this->getUsersList();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    protected function getUsersList()
    {
        $arUsers = YlabUsersTable::getList([
            'select' => ['ID', 'NAME', 'CITY', 'PHONE', 'DATE_BIRTH']
        ])->fetchAll();

        return $arUsers;
    }
}
