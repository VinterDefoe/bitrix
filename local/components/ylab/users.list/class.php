<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        /**
         * @global \CMain $APPLICATION
         */
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult = $this->getUsersList();

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    protected function getUsersList()
    {
        try {
            Loader::includeModule("iblock");
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $arResult = [];

        $params = [
            'select' => ['ID', 'NAME'],
            'filter' => ['IBLOCK_ID' => 1]
        ];

        try {
            $arResult = ElementTable::getList($params)->fetchAll();
        } catch (ObjectPropertyException $e) {
            echo $e->getMessage();
        } catch (ArgumentException $e) {
            echo $e->getMessage();
        } catch (SystemException $e) {
            echo $e->getMessage();
        }

        return $arResult;
    }
}
