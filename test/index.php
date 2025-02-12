<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Title");?>

<?php
$APPLICATION->IncludeComponent(
    'custom:fortune.wheel',
    '',
    array(
        'FIRST_START' => 'N',
        'PRIZE_IBLOCK_ID' => '39',
        'RESULT_IBLOCK_ID' => '40',
    )
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>