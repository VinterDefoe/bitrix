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

<table border="1">
    <thead>
    <tr>
        <td> <?= Loc::getMessage('USERS_ID') ?></td>
        <td> <?= Loc::getMessage('USERS_NAME') ?></td>
        <td> <?= Loc::getMessage('USERS_CITY') ?></td>
        <td> <?= Loc::getMessage('USERS_PHONE') ?></td>
        <td> <?= Loc::getMessage('USERS_DATE') ?></td>
    </tr>
    </thead>
    <tbody>
    <? foreach ($arResult as $user): ?>
        <tr>
            <td><?= $user['ID'] ?></td>
            <td><?= $user['NAME'] ?></td>
            <td><?= $user['CITY'] ?></td>
            <td><?= $user['PHONE'] ?></td>
            <td>
                <?
                /**
                 * @var \Bitrix\Main\Type\Date $date
                 */
                $date = $user['DATE_BIRTH'];
                echo $date->format('d.m.Y');
                ?>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>
