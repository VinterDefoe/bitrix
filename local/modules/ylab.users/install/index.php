<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);


/**
 * Class ylab_users
 */
class ylab_users extends CModule
{
    /**
     * @var string Код модуля
     */
    var $MODULE_ID = 'ylab.users';

    /**
     * ylab_users constructor.
     */
    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('YLAB_USERS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('YLAB_USERS_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    /**
     *  Do install
     * @throws SystemException
     */
    public function DoInstall()
    {
        /** \CMain $APPLICATION */
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);

        if(!ModuleManager::isModuleInstalled('ylab.validation'))
        {
            throw new SystemException('Not install module ylab.validation');
        }

        $this->installComponents();

        try {
            $this->InstallDB();
        } catch (Exception $e) {
            $APPLICATION->ThrowException($e->getMessage());
        }
        $oException = $APPLICATION->GetException();
        if ($oException) {
            return false;
        }
        return true;
    }

    /**
     * Copy components
     */
    private function installComponents()
    {
        if (!\Bitrix\Main\IO\Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/local/components/ylab')) {
            \Bitrix\Main\IO\Directory::createDirectory($_SERVER['DOCUMENT_ROOT'] . '/local/components/ylab');
        }
        CopyDirFiles(dirname(__DIR__) . '/install/components/ylab',
            $_SERVER['DOCUMENT_ROOT'] . '/local/components/ylab',
            true,
            true
        );
    }

    /**
     * @return bool|void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $bTableExist = Application::getConnection(\YLab\Users\YlabUsersTable::getConnectionName())
            ->isTableExists(
                Base::getInstance('\YLab\Users\YlabUsersTable')->getDBTableName()
            );
        if (!$bTableExist) {
            Base::getInstance('\YLab\Users\YlabUsersTable')->createDbTable();
        }
    }

    /**
     * Do uninstall
     */
    public function DoUninstall()
    {
        /** \CMain $APPLICATION */
        global $APPLICATION;

        $this->uninstallComponents();

        try {
            $this->UnInstallDB();
        } catch (Exception $e) {
            $APPLICATION->ThrowException($e->getMessage());
        }

        $oException = $APPLICATION->GetException();
        if ($oException) {
            return false;
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     *  Delete components
     */
    private function uninstallComponents()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/local/components/ylab/users.add');
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/local/components/ylab/users.list');

    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\Db\SqlQueryException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\YLab\Users\YlabUsersTable::getConnectionName())
            ->queryExecute('drop table if exists ' . Base::getInstance('\YLab\Users\YlabUsersTable')->getDBTableName());
    }
}