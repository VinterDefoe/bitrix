<?php
/**
 * @global \CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <?elseif ($arResult['SUCCESS']):?>
        <p>Успешная валидация</p>
    <? endif; ?>
    <div>
        <label>
            Город<br>
            <select name="city">
                <option value="">Выбрать</option>
                <? $index = 1;?>
                <? foreach ($arResult['CITY'] as $city ): ?>
                    <option value="<?=$index?>"><?=$city['VALUE']?></option>
                    <? $index++;?>
                <? endforeach ?>
            </select>
        </label>
    </div>
    <div>
        <label>
            Дата<br>
            <input type="text" name="date"/>
        </label>
    </div>
    <div>
        <label>
            Номере телефона<br>
            <input type="text" name="phone"/>
        </label>
    </div>
    <div>
        <label>
            Имя<br>
            <input type="text" name="name"/>
        </label>
    </div>
    <div class="btn green">
        <button type="submit" name="submit">Добавить</button>
    </div>
</form>
