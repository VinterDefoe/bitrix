<?php


use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Phinx\Migration\AbstractMigration;

class UsersIblock extends AbstractMigration
{
    /**
     * @var string
     */
    public $sIblockTypeID = 'tt_users_iblock_type';

    /**
     * @var string
     */
    public $sIblockCode = 'tt_users_iblock';

    /**
     * @var string
     */
    public $sSite = 's1';

    /**
     * @var int
     */
    protected $iIblockID;

    /**
     *  Init
     */
    public function init()
    {
        try {
            Loader::includeModule("iblock");
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return int
     */
    private function getIblockID()
    {
        if (isset($this->iIblockID)) {
            return $this->iIblockID;
        }

        $oIblick = CIBlock::GetList([], [
            'TYPE' => $this->sIblockTypeID,
            'CODE' => $this->sIblockCode,
            "CHECK_PERMISSIONS" => "N"
        ]);

        $arResult = $oIblick->Fetch();

        if ($arResult['ID'] && $arResult['ID'] > 0) {
            $this->iIblockID = (int)$arResult['ID'];
            return (int)$arResult['ID'];
        }
        return false;
    }
    /**
     * Migrate Up.
     * @throws Exception
     */
    public function up()
    {
        $this->addIblockType();

        $this->addIblock();

//        $f = $this->getIblockID();
//        var_dump($f);

        $this->addIblockProperty();
    }

    /**
     * @throws Exception
     */
    private function addIblockType()
    {
        if ($this->isExistIblockType()) {
            throw new Exception('Iblock Type already exist');
        }
        $arFields = Array(
            'ID' => $this->sIblockTypeID,
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'en' => [
                    'NAME' => 'Users',
                    'SECTION_NAME' => '',
                    'ELEMENT_NAME' => ''
                ],
                'ru' => [
                    'NAME' => 'Пользователи',
                    'SECTION_NAME' => '',
                    'ELEMENT_NAME' => ''
                ]
            ]
        );

        $oIblickType = new CIBlockType();

        $bResult = $oIblickType->Add($arFields);

        if (!$bResult) {
            throw new Exception('Error by adding Iblock Type');
        }
    }

    /**
     * @return bool
     */
    private function isExistIblockType()
    {
        $arFields = [
            'ID' => $this->sIblockTypeID
        ];

        $oIblickType = CIBlockType::GetList([], $arFields);

        $arResult = $oIblickType->Fetch();

        if (!empty($arResult)) {
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function addIblock()
    {
        $arIblickSettings = [
            'CODE' => [
                'NAME' => 'Символьный код',
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => [
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '-',
                    'TRANS_OTHER' => '-',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'N'
                ]
            ]
        ];
        $arFields = Array(
            'ACTIVE' => 'Y',
            'NAME' => 'Пользователи',
            'CODE' => $this->sIblockCode,
            'LIST_PAGE_URL' => '#SITE_DIR#/users/index.php?ID=#IBLOCK_ID#',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/users/detail.php?ID=#ELEMENT_ID#',
            'SECTION_PAGE_URL' => '#SITE_DIR#/users/list.php?SECTION_ID=#SECTION_ID#',
            'IBLOCK_TYPE_ID' => $this->sIblockTypeID,
            'SITE_ID' => $this->sSite,
            'SORT' => 500,
            'PICTURE' => null,
            'DESCRIPTION' => '',
            'DESCRIPTION_TYPE' => 'text',
            'GROUP_ID' => ['1' => 'X'],
            'FIELDS' => $arIblickSettings
        );

        $oIblock = new CIBlock();

        $Id = $oIblock->Add($arFields);

        if (!$Id) {
            throw new Exception('Error by adding Iblock');
        }
        $this->iIblockID = $Id;
    }

    /**
     * @throws Exception
     */
    private function addIblockProperty()
    {
        $iIblockID = $this->getIblockID();

        if (!$iIblockID) {
            throw new Exception('Error by getting Iblock ID');
        }

        $oIblockProperty = new CIBlockProperty;

        $arFields = [
            [
                "NAME" => "Город",
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => "CITY",
                "PROPERTY_TYPE" => "L",
                "IBLOCK_ID" => $iIblockID,
                "VALUES" => [
                    [
                        "VALUE" => "Москва",
                        "DEF" => "N",
                        "SORT" => "100"
                    ],
                    [
                        "VALUE" => "Санкт-Петербург",
                        "DEF" => "N",
                        "SORT" => "100"
                    ],
                    [
                        "VALUE" => "Казань",
                        "DEF" => "N",
                        "SORT" => "100"
                    ]
                ]
            ],
            [
                "NAME" => "Номер телефона",
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => "TELEPHONE_NUMBER",
                "PROPERTY_TYPE" => "N",
                "IBLOCK_ID" => $iIblockID
            ],
            [
                "NAME" => "Дата рождения",
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => "DATE_OF_BIRTH",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $iIblockID
            ]
        ];

        foreach ($arFields as $arSubFields) {

            $propertyId = $oIblockProperty->Add($arSubFields);

            if (!$propertyId) {
                throw new Exception('Error by adding Property');
            }
        }
    }


    /**
     * Migrate Down.
     * @throws Exception
     */
    public function down()
    {
        $this->deleteIblockProperty();

        $this->deleteIblock();

        $this->deleteIblockType();
    }

    /**
     * @throws Exception
     */
    private function deleteIblockProperty()
    {
        $arProperty = $this->getPropertiesID();
        if (empty($arProperty)) {
            throw new Exception('Error by getting Property array');
        }

        foreach ($arProperty as $property) {
            if (!CIBlockType::Delete($property)) {
                throw new Exception('Error by deleting Iblock Property');
            }
        }
    }

    /**
     * @throws Exception
     */
    private function getPropertiesID()
    {
        $iIblockID = $this->getIblockID();
        if (!$iIblockID) {
            throw new Exception('Error by getting Iblock ID');
        }
        $oIblickProperty = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iIblockID]);

        $arProperty = [];

        while ($arTemp = $oIblickProperty->Fetch()) {
            $arProperty[] = $arTemp ['ID'];
        }

        return $arProperty;
    }

    /**
     * @throws Exception
     */
    private function deleteIblock()
    {
        $iIblockID = $this->getIblockID();
        if (!$iIblockID) {
            throw new Exception('Error by getting Iblock ID');
        }
        if (!CIBlock::Delete($iIblockID)) {
            throw new Exception('Error by deleting Iblock');
        }
    }

    /**
     * @throws Exception
     */
    private function deleteIblockType()
    {
        if (!CIBlockType::Delete($this->sIblockTypeID)) {
            throw new Exception('Error by deleting Iblock Type');
        }
    }
}
