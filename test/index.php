<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Title");?>

<?php
$APPLICATION->IncludeComponent(
    'custom:fortune.wheel',
    '',
    []
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>