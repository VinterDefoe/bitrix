<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/ylab.validation/lib/componentvalidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/ylab.validation/lib/validatorhelper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/ylab.validation/lib/validator.php';

/**
 * Class UsersListComponent
 */
class UsersAddComponent extends ComponentValidation
{
    /**
     * @var int Change this
     */
    public $iIblockID = 27;

    /**
     * ValidationTestComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        try {
            Loader::includeModule("iblock");
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $this->arResult = $this->getCityList();

        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;
                $this->addUser($this->oRequest->toArray());
            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @param $oRequest
     * @return bool
     */
    protected function addUser($oRequest)
    {
        $oElement = new CIBlockElement;

        $arProp = [];

        $arProp['CITY'] = ['VALUE' => $oRequest['city']];
        $arProp['DATE_OF_BIRTH'] = $oRequest['date'];
        $arProp['TELEPHONE_NUMBER'] = $oRequest['phone'];

        $arElementFilds = [
            'IBLOCK_ID' => $this->iIblockID,
            'PROPERTY_VALUES' => $arProp,
            'NAME' => $oRequest['name'],
            'ACTIVE' => 'Y',
        ];

        if($bResult = $oElement->Add($arElementFilds)){
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    protected function getCityList()
    {
        $arPropery = $this->getProperty('CITY', $this->iIblockID);

        $arResult = [];

        $arParams = [
            'select' => ['VALUE'],
            'filter' => ['PROPERTY_ID' => $arPropery['ID']]
        ];

        try {
            $arResult['CITY'] = PropertyEnumerationTable::getList($arParams)->fetchAll();
        } catch (ObjectPropertyException $e) {
            echo $e->getMessage();
        } catch (ArgumentException $e) {
            echo $e->getMessage();
        } catch (SystemException $e) {
            echo $e->getMessage();
        }

        return array_merge($arPropery, $arResult);
    }

    /**
     * @param $sCode
     * @param $iIblockId
     * @return array|false
     */
    protected function getProperty($sCode, $iIblockId)
    {
        $arPropery = [];

        $arParams = [
            'select' => ['ID', 'NAME'],
            'filter' => ['IBLOCK_ID' => $iIblockId, 'CODE' => $sCode]
        ];

        try {
            $arPropery = PropertyTable::getList($arParams)->fetchRaw();
        } catch (ObjectPropertyException $e) {
            echo $e->getMessage();
        } catch (ArgumentException $e) {
            echo $e->getMessage();
        } catch (SystemException $e) {
            echo $e->getMessage();
        }

        return $arPropery;
    }

    /**
     * @return array 
     */
    protected function rules()
    {
        return [
            'city' => 'required|numeric',
            'date' => 'required|date_format:d.m.Y',
            'phone' => 'required|regex:/^(\+7)[0-9]{10}$/',
            'name' => 'required|max:100'
        ];
    }
}
