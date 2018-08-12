<?php


use Phinx\Migration\AbstractMigration;

/**
 *  MIGRATE FOR USERS IBLOCK
 *
 * Class IblockForUsers
 */
class IblockForUsers extends AbstractMigration
{

    /**
     * @var string
     */
    public $sIblockTypeID = 'pd_users_iblock_type';

    /**
     * @var string
     */
    public $sIblockCode = 'pd_users_iblock';

    /**
     * @var string
     */
    public $sSite = 's1';

    /**
     * @var int
     */
    protected $iIblockID;

    /**
     * Migrate Up
     * @throws Exception
     */
    public function up()
    {
        $this->insertIntoIblockTypeTable();

        $this->insertIntoIblockTypeLangTable();

        $this->insertIntoIblockTable();

        $this->insertIntoIblockSiteTable();

        $this->insertIntoIblockMessagesTable();

        $this->insertIntoIblockGroupTable();

        $this->insertIntoIblockFieldsTable();

        $this->insertIntoIblockPropertyTable();

        $this->insertIntoIblockPropertyEnumTable();
    }

    /**
     * insert Into b_iblock_type
     */
    private function insertIntoIblockTypeTable()
    {
        $arIblockType = [
            'ID' => $this->sIblockTypeID,
            'SECTIONS' => 'Y',
            'EDIT_FILE_BEFORE' => '',
            'EDIT_FILE_AFTER' => '',
            'IN_RSS' => 'N',
            'SORT' => 500
        ];

        $table = $this->table('b_iblock_type');
        $table->insert($arIblockType);
        $table->saveData();
    }

    /**
     * insert Into b_iblock_type_lang
     */
    private function insertIntoIblockTypeLangTable()
    {
        $arParams = [
            [
                'IBLOCK_TYPE_ID' => $this->sIblockTypeID,
                'LID' => 'en',
                'NAME' => 'Users',
                'SECTION_NAME' => '',
                'ELEMENT_NAME' => ''
            ],
            [
                'IBLOCK_TYPE_ID' => $this->sIblockTypeID,
                'LID' => 'ru',
                'NAME' => 'Пользователи',
                'SECTION_NAME' => '',
                'ELEMENT_NAME' => ''
            ]
        ];

        $table = $this->table('b_iblock_type_lang');
        $table->insert($arParams);
        $table->saveData();
    }

    /**
     * insert Into b_iblock
     */
    private function insertIntoIblockTable()
    {
        $arParams = [
//            'ID'=> '',
//            'TIMESTAMP_X' => '2018-08-05 12:47:07',
            'IBLOCK_TYPE_ID' => $this->sIblockTypeID,
            'LID' => $this->sSite,
            'CODE' => $this->sIblockCode,
            'NAME' => 'Пользователи',
            'ACTIVE' => 'Y',
            'SORT' => 500,
            'LIST_PAGE_URL' => '#SITE_DIR#/users/index.php?ID=#IBLOCK_ID#',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/users/detail.php?ID=#ELEMENT_ID#',
            'SECTION_PAGE_URL' => '#SITE_DIR#/users/list.php?SECTION_ID=#SECTION_ID#',
            'CANONICAL_PAGE_URL' => '',
            'PICTURE' => null,
            'DESCRIPTION' => '',
            'DESCRIPTION_TYPE' => 'text',
            'RSS_TTL' => 24,
            'RSS_ACTIVE' => 'Y',
            'RSS_FILE_ACTIVE' => 'N',
            'RSS_FILE_LIMIT' => null,
            'RSS_FILE_DAYS' => null,
            'RSS_YANDEX_ACTIVE' => 'N',
            'XML_ID' => null,
            'TMP_ID' => null,
            'INDEX_ELEMENT' => 'Y',
            'INDEX_SECTION' => 'Y',
            'WORKFLOW' => 'N',
            'BIZPROC' => 'N',
            'SECTION_CHOOSER' => 'L',
            'LIST_MODE' => '',
            'RIGHTS_MODE' => 'S',
            'SECTION_PROPERTY' => 'N',
            'PROPERTY_INDEX' => 'N',
            'VERSION' => 1,
            'LAST_CONV_ELEMENT' => 0,
            'SOCNET_GROUP_ID' => null,
            'EDIT_FILE_BEFORE' => '',
            'EDIT_FILE_AFTER' => '',
            'SECTIONS_NAME' => 'Разделы',
            'SECTION_NAME' => 'Раздел',
            'ELEMENTS_NAME' => 'Элементы',
            'ELEMENT_NAME' => 'Элемент'
        ];

        $table = $this->table('b_iblock');
        $table->insert($arParams);
        $table->saveData();
    }

    /**
     * insert Into b_iblock_site
     * @throws Exception
     */
    private function insertIntoIblockSiteTable()
    {
        $arParams = [
            'IBLOCK_ID' => $this->getIblockID(),
            'SITE_ID' => $this->sSite
        ];

        $table = $this->table('b_iblock_site');
        $table->insert($arParams);
        $table->saveData();
    }

    /**
     * @return int IblockID
     * @throws Exception
     */
    private function getIblockID()
    {
        if (isset($this->iIblockID)) {
            return $this->iIblockID;
        }
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("SELECT ID FROM b_iblock WHERE CODE = :sIblockCode AND IBLOCK_TYPE_ID =:sIblockTypeID;");
        $oSth->bindParam(':sIblockCode', $this->sIblockCode);
        $oSth->bindParam(':sIblockTypeID', $this->sIblockTypeID);
        $oSth->execute();
        $iIblockID = (int)$oSth->fetchColumn();
        if (!$iIblockID) {
            throw new Exception("Error by getting IblockID");
        }
        return $this->iIblockID = $iIblockID;
    }

    /**
     * insert Into b_iblock_messages
     * @throws Exception
     */
    private function insertIntoIblockMessagesTable()
    {
        $iIblockID = $this->getIblockID();

        $arParams = [
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'ELEMENT_ADD',
                'MESSAGE_TEXT' => 'Добавить элемент'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'ELEMENT_DELETE',
                'MESSAGE_TEXT' => 'Удалить элемент'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'ELEMENT_EDIT',
                'MESSAGE_TEXT' => 'Изменить элемент'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'ELEMENT_NAME',
                'MESSAGE_TEXT' => 'Элемент'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'ELEMENTS_NAME',
                'MESSAGE_TEXT' => 'Элементы'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'SECTION_ADD',
                'MESSAGE_TEXT' => 'Добавить раздел'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'SECTION_DELETE',
                'MESSAGE_TEXT' => 'Удалить раздел'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'SECTION_EDIT',
                'MESSAGE_TEXT' => 'Изменить раздел'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'SECTION_NAME',
                'MESSAGE_TEXT' => 'Раздел'
            ],
            [
                'IBLOCK_ID' => $iIblockID,
                'MESSAGE_ID' => 'SECTIONS_NAME',
                'MESSAGE_TEXT' => 'Разделы'
            ],
        ];

        $this->table('b_iblock_messages')->insert($arParams)->save();
    }

    /**
     * insert Into b_iblock_group
     * @throws Exception
     */
    private function insertIntoIblockGroupTable()
    {
        $arParams = [
            'IBLOCK_ID' => $this->getIblockID(),
            'GROUP_ID' => 1,
            'PERMISSION' => 'X'
        ];

        $this->table('b_iblock_group')->insert($arParams)->save();
    }

    /**
     * insert Into b_iblock_fields
     * @throws Exception
     */
    private function insertIntoIblockFieldsTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare('
            INSERT INTO `b_iblock_fields` (`IBLOCK_ID`, `FIELD_ID`, `IS_REQUIRED`, `DEFAULT_VALUE`) VALUES
(:iIblockID,	\'ACTIVE\',	\'Y\',	\'Y\'),
(:iIblockID,	\'ACTIVE_FROM\',	\'N\',	\'\'),
(:iIblockID,	\'ACTIVE_TO\',	\'N\',	\'\'),
(:iIblockID,	\'CODE\',	\'N\',	\'a:8:{s:6:\"UNIQUE\";s:1:\"N\";s:15:\"TRANSLITERATION\";s:1:\"N\";s:9:\"TRANS_LEN\";i:100;s:10:\"TRANS_CASE\";s:1:\"L\";s:11:\"TRANS_SPACE\";s:1:\"-\";s:11:\"TRANS_OTHER\";s:1:\"-\";s:9:\"TRANS_EAT\";s:1:\"Y\";s:10:\"USE_GOOGLE\";s:1:\"N\";}\'),
(:iIblockID,	\'DETAIL_PICTURE\',	\'N\',	\'a:17:{s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}\'),
(:iIblockID,	\'DETAIL_TEXT\',	\'N\',	\'\'),
(:iIblockID,	\'DETAIL_TEXT_TYPE\',	\'Y\',	\'text\'),
(:iIblockID,	\'DETAIL_TEXT_TYPE_ALLOW_CHANGE\',	\'N\',	\'Y\'),
(:iIblockID,	\'IBLOCK_SECTION\',	\'N\',	\'a:1:{s:22:\"KEEP_IBLOCK_SECTION_ID\";s:1:\"N\";}\'),
(:iIblockID,	\'LOG_ELEMENT_ADD\',	\'N\',	NULL),
(:iIblockID,	\'LOG_ELEMENT_DELETE\',	\'N\',	NULL),
(:iIblockID,	\'LOG_ELEMENT_EDIT\',	\'N\',	NULL),
(:iIblockID,	\'LOG_SECTION_ADD\',	\'N\',	NULL),
(:iIblockID,	\'LOG_SECTION_DELETE\',	\'N\',	NULL),
(:iIblockID,	\'LOG_SECTION_EDIT\',	\'N\',	NULL),
(:iIblockID,	\'NAME\',	\'Y\',	\'\'),
(:iIblockID,	\'PREVIEW_PICTURE\',	\'N\',	\'a:20:{s:11:\"FROM_DETAIL\";s:1:\"N\";s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"DELETE_WITH_DETAIL\";s:1:\"N\";s:18:\"UPDATE_WITH_DETAIL\";s:1:\"N\";s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}\'),
(:iIblockID,	\'PREVIEW_TEXT\',	\'N\',	\'\'),
(:iIblockID,	\'PREVIEW_TEXT_TYPE\',	\'Y\',	\'text\'),
(:iIblockID,	\'PREVIEW_TEXT_TYPE_ALLOW_CHANGE\',	\'N\',	\'Y\'),
(:iIblockID,	\'SECTION_CODE\',	\'Y\',	\'a:8:{s:6:\"UNIQUE\";s:1:\"Y\";s:15:\"TRANSLITERATION\";s:1:\"Y\";s:9:\"TRANS_LEN\";i:100;s:10:\"TRANS_CASE\";s:1:\"L\";s:11:\"TRANS_SPACE\";s:1:\"-\";s:11:\"TRANS_OTHER\";s:1:\"-\";s:9:\"TRANS_EAT\";s:1:\"Y\";s:10:\"USE_GOOGLE\";s:1:\"N\";}\'),
(:iIblockID,	\'SECTION_DESCRIPTION\',	\'N\',	\'\'),
(:iIblockID,	\'SECTION_DESCRIPTION_TYPE\',	\'Y\',	\'html\'),
(:iIblockID,	\'SECTION_DESCRIPTION_TYPE_ALLOW_CHANGE\',	\'N\',	\'Y\'),
(:iIblockID,	\'SECTION_DETAIL_PICTURE\',	\'N\',	\'a:17:{s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}\'),
(:iIblockID,	\'SECTION_NAME\',	\'Y\',	\'\'),
(:iIblockID,	\'SECTION_PICTURE\',	\'N\',	\'a:20:{s:11:\"FROM_DETAIL\";s:1:\"N\";s:5:\"SCALE\";s:1:\"N\";s:5:\"WIDTH\";s:0:\"\";s:6:\"HEIGHT\";s:0:\"\";s:13:\"IGNORE_ERRORS\";s:1:\"N\";s:6:\"METHOD\";s:8:\"resample\";s:11:\"COMPRESSION\";i:95;s:18:\"DELETE_WITH_DETAIL\";s:1:\"N\";s:18:\"UPDATE_WITH_DETAIL\";s:1:\"N\";s:18:\"USE_WATERMARK_TEXT\";s:1:\"N\";s:14:\"WATERMARK_TEXT\";s:0:\"\";s:19:\"WATERMARK_TEXT_FONT\";s:0:\"\";s:20:\"WATERMARK_TEXT_COLOR\";s:0:\"\";s:19:\"WATERMARK_TEXT_SIZE\";s:0:\"\";s:23:\"WATERMARK_TEXT_POSITION\";s:2:\"tl\";s:18:\"USE_WATERMARK_FILE\";s:1:\"N\";s:14:\"WATERMARK_FILE\";s:0:\"\";s:20:\"WATERMARK_FILE_ALPHA\";s:0:\"\";s:23:\"WATERMARK_FILE_POSITION\";s:2:\"tl\";s:20:\"WATERMARK_FILE_ORDER\";N;}\'),
(:iIblockID,	\'SECTION_XML_ID\',	\'N\',	\'\'),
(:iIblockID,	\'SORT\',	\'N\',	\'0\'),
(:iIblockID,	\'TAGS\',	\'N\',	\'\'),
(:iIblockID,	\'XML_ID\',	\'Y\',	\'\'),
(:iIblockID,	\'XML_IMPORT_START_TIME\',	\'N\',	NULL);
        ');
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * insert Into b_iblock_property
     * @throws Exception
     */
    private function insertIntoIblockPropertyTable()
    {
        $arParams = [
            [
                //            'ID' => 1,
//            'TIMESTAMP_X' => '2018-08-09 16:47:01',
                'IBLOCK_ID' => $this->getIblockID(),
                'NAME' => 'Дата рождения',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'DATE_OF_BIRTH',
                'DEFAULT_VALUE' => null,
                'PROPERTY_TYPE' => 'S',
                'ROW_COUNT' => 1,
                'COL_COUNT' => 30,
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => null,
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => 5,
                'TMP_ID' => null,
                'LINK_IBLOCK_ID' => 0,
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => 1,
                'USER_TYPE' => 'Date',
                'USER_TYPE_SETTINGS' => null,
                'HINT' => ''
            ],
            [
                //            'ID' => 1,
//            'TIMESTAMP_X' => '2018-08-09 16:47:01',
                'IBLOCK_ID' => $this->getIblockID(),
                'NAME' => 'Номер телефона',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'TELEPHONE_NUMBER',
                'DEFAULT_VALUE' => '',
                'PROPERTY_TYPE' => 'N',
                'ROW_COUNT' => 1,
                'COL_COUNT' => 30,
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => null,
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => 5,
                'TMP_ID' => null,
                'LINK_IBLOCK_ID' => 0,
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => 1,
                'USER_TYPE' => null,
                'USER_TYPE_SETTINGS' => null,
                'HINT' => ''
            ],
            [
                //            'ID' => 1,
//            'TIMESTAMP_X' => '2018-08-09 16:47:01',
                'IBLOCK_ID' => $this->getIblockID(),
                'NAME' => 'Город',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'CITY',
                'DEFAULT_VALUE' => '',
                'PROPERTY_TYPE' => 'L',
                'ROW_COUNT' => 1,
                'COL_COUNT' => 30,
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'XML_ID' => null,
                'FILE_TYPE' => '',
                'MULTIPLE_CNT' => 5,
                'TMP_ID' => null,
                'LINK_IBLOCK_ID' => 0,
                'WITH_DESCRIPTION' => 'N',
                'SEARCHABLE' => 'N',
                'FILTRABLE' => 'N',
                'IS_REQUIRED' => 'N',
                'VERSION' => 1,
                'USER_TYPE' => null,
                'USER_TYPE_SETTINGS' => null,
                'HINT' => ''
            ],

        ];

        $this->table('b_iblock_property')->insert($arParams)->save();
    }

    /**
     * Migrate Down.
     * @throws Exception
     */
    public function down()
    {
        $this->deleteFromIblockTypeTable();

        $this->deleteFromIblockTypeLangTable();

        $this->deleteFromIblockPropertyEnumTable();

        $this->deleteFromIblockPropertyTable();

        $this->deleteFromIblockFieldsTable();

        $this->deleteFromIblockGroupTable();

        $this->deleteFromIblockMessagesTable();

        $this->deleteFromIblockSiteTable();

        $this->deleteFromIblockTable();
    }

    /**
     * delete b_iblock_type
     */
    private function deleteFromIblockTypeTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_type WHERE ID = :sIblockTypeID;");
        $oSth->bindParam(':sIblockTypeID', $this->sIblockTypeID);
        $oSth->execute();
    }

    /**
     *  delete From b_iblock_type_lang
     */
    private function deleteFromIblockTypeLangTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_type_lang WHERE IBLOCK_TYPE_ID = :sIblockTypeID;");
        $oSth->bindParam(':sIblockTypeID', $this->sIblockTypeID);
        $oSth->execute();
    }

    /**
     * delete From b_iblock_property
     * @throws Exception
     */
    private function deleteFromIblockPropertyTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_property WHERE IBLOCK_ID = :iIblockID;");
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * delete From b_iblock_fields
     * @throws Exception
     */
    private function deleteFromIblockFieldsTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_fields WHERE IBLOCK_ID = :iIblockID;");
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * delete From b_iblock_group
     * @throws Exception
     */
    private function deleteFromIblockGroupTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_group WHERE IBLOCK_ID = :iIblockID;");
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * delete From b_iblock_messages
     * @throws Exception
     */
    private function deleteFromIblockMessagesTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_messages WHERE IBLOCK_ID = :iIblockID;");
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * delete From b_iblock_site
     * @throws Exception
     */
    private function deleteFromIblockSiteTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_site WHERE IBLOCK_ID = :iIblockID;");
        $oSth->bindParam(':iIblockID', $this->getIblockID());
        $oSth->execute();
    }

    /**
     * delete From b_iblock
     */
    private function deleteFromIblockTable()
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock WHERE CODE = :sIblockCode;");
        $oSth->bindParam(':sIblockCode', $this->sIblockCode);
        $oSth->execute();
    }

    /**
     * Insert Into b_iblock_property_enum
     * @throws Exception
     */
    private function insertIntoIblockPropertyEnumTable()
    {
        $iPropertyID = $this->getIblockPropertyID('CITY');

        $arParams = [
            [
//                'ID' => 1,
                'PROPERTY_ID' => $iPropertyID,
                'VALUE' => 'Москва',
                'DEF' => 'N',
                'SORT' => 500,
                'XML_ID' => 'MOSCOW',
                'TMP_ID' => null,
            ],
            [
//                'ID' => 1,
                'PROPERTY_ID' => $iPropertyID,
                'VALUE' => 'Санкт-Петербург',
                'DEF' => 'N',
                'SORT' => 500,
                'XML_ID' => 'PETERSBURG',
                'TMP_ID' => null,
            ],
            [
//                'ID' => 1,
                'PROPERTY_ID' => $iPropertyID,
                'VALUE' => 'Казань',
                'DEF' => 'N',
                'SORT' => 500,
                'XML_ID' => 'KAZAN',
                'TMP_ID' => null,
            ]
        ];

        $this->table('b_iblock_property_enum')->insert($arParams)->save();
    }

    /**
     * @param $sPropertyCode
     * @return int
     * @throws Exception
     */
    private function getIblockPropertyID($sPropertyCode)
    {
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("SELECT ID FROM b_iblock_property WHERE CODE = :sPropertyCode AND IBLOCK_ID =:sIblockID;");
        $oSth->bindParam(':sPropertyCode', $sPropertyCode);
        $oSth->bindParam(':sIblockID', $this->getIblockID());
        $oSth->execute();
        $iIblockPropertyID = (int)$oSth->fetchColumn();
        if (!$iIblockPropertyID) {
            throw new Exception("Error by getting IblockPropertyID");
        }
        return $iIblockPropertyID;
    }

    /**
     * Delete From b_iblock_property_enum
     * @throws Exception
     */
    private function deleteFromIblockPropertyEnumTable()
    {
        $iPropertyID = $this->getIblockPropertyID('CITY');
        $oPDO = $this->getAdapter()->getConnection();
        $oSth = $oPDO->prepare("DELETE FROM b_iblock_property_enum WHERE PROPERTY_ID = :iPropertyID;");
        $oSth->bindParam(':iPropertyID', $iPropertyID);
        $oSth->execute();
    }
}
