<?php
/**
 * @global \CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <? elseif ($arResult['SUCCESS']): ?>
        <?= Loc::getMessage('USERS_SUCCESS_VALIDATION') ?>
    <? endif; ?>
    <div>
        <label>
            <?= Loc::getMessage('USERS_CITY') ?><br>
            <input type="text" name="city"/>
        </label>
    </div>
    <div>
        <label>
            <?= Loc::getMessage('USERS_DATE') ?><br>
            <input type="text" name="date"/>
        </label>
    </div>
    <div>
        <label>
            <?= Loc::getMessage('USERS_PHONE') ?><br>
            <input type="text" name="phone"/>
        </label>
    </div>
    <div>
        <label>
            <?= Loc::getMessage('USERS_NAME') ?><br>
            <input type="text" name="name"/>
        </label>
    </div>
    <div class="btn green">
        <button type="submit" name="submit"><?= Loc::getMessage('USERS_BUTTON_ADD') ?></button>
    </div>
</form>
