<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ошибка: 404 - Страница не найдена");
?>
<style>
.page-top, .left-menu-md, .right-menu-md{display:none;}
.content-md{width:100%;}
</style>
<div class="page404">
	<div class="row">
		<div class="col-md-7 hidden-xs hidden-sm">
			<div class="i404">404</div>
		</div>
		<div class="col-md-5 col-sm-12">
			<div class="t404">
				<div style="font-size:44px;line-height:50px;text-transform:uppercase;">Ошибка 404</div>
				<div style="font-size:18px;line-height:32px;color:#666666;">Страница не найдена</div>
				<div style="font-size:13px;margin:14px 0 35px;">Неправильно набран адрес или такой<br />страницы не существует</div>
				<a href="<?=SITE_DIR?>" class="btn btn-default btn-lg">Перейти на главную</a>
				<?if($_SERVER["HTTP_REFERER"]):?>
					<div>
						или <a href="<?=$_SERVER["HTTP_REFERER"]?>">вернуться назад</a>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(window).resize(function(){ // BX.addCustomEvent('onWindowResize', function(eventdata) {
	try{
		var windowHeight = $(window).outerHeight();
		var headerHeight = $('header').outerHeight();
		var footerHeight = $('footer').outerHeight();
		var page404Height = $('.page404').outerHeight();
		var part = Math.floor((windowHeight - headerHeight - footerHeight - page404Height - 28) / 2);
		
		if(part < 0){
			part = 0;
		}
		
		ignoreResize.push(true);
		
		$('.page404').css({'opacity': '1', 'margin-top': part + 'px', 'margin-bottom': part + 'px'});
		
		setTimeout(function() {
			$('.page404').css({'transition': 'none', '-moz-transition': 'none', '-ms-transition': 'none', '-o-transition': 'none', '-webkit-transition': 'none'});
		}, 400);
		ignoreResize.pop();
	}
	catch(e){}
});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>