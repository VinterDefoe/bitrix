<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date;
use Ylab\Users\YlabUsersTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

try {
    Loader::includeModule("ylab.validation");
} catch (LoaderException $e) {
    echo $e->getMessage();
}

/**
 * Class UsersAddComponent
 */
class UsersAddComponent extends ComponentValidation
{
    /**
     * UsersAddComponent constructor.
     * @param CBitrixComponent|null $component
     * @param string $sFile
     * @throws Exception
     * @throws SystemException
     * @throws \Bitrix\Main\IO\InvalidPathException
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
            Loader::includeModule("ylab.users");
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;
                try {
                    $this->addUser($this->oRequest->toArray());
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @param $oRequest
     * @throws Exception
     */
    protected function addUser($oRequest)
    {
        $oResult = YlabUsersTable::add([
            'NAME' => $oRequest['name'],
            'CITY' => $oRequest['city'],
            'PHONE' => $oRequest['phone'],
            'DATE_BIRTH' => new Date($oRequest['date'], 'd.m.Y'),
        ]);
        if (!$oResult->isSuccess()) {
            throw new Exception('Error by adding');
        }
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'city' => 'required|max:50',
            'date' => 'required|date_format:d.m.Y',
            'phone' => 'required|regex:/^(\+7)[0-9]{10}$/',
            'name' => 'required|max:100'
        ];
    }
}
