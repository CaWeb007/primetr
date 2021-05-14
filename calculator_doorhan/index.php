<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Калькулятор");?>
<style>
	.left-menu-md{
		display: none !important;
	}
	.content-md{
		width: 100% !important;
	}
</style>
<div id="dhaide">
<!--[if gt IE 8]>-->
<script>window.jQuery || document.write('<script type="text/javascript" src="https://aide.doorhan.ru/dhaide/js/vendor/jquery.js"><\/script>');</script>

<script>
	(function (t, h, e, A, I, D, E) {
		D=h.createElement(e);D.async=1;D.setAttribute("crossorigin","use-credentials");
		D.src=A+"?data="+encodeURIComponent(JSON.stringify(I));I.src=A;t.dhAide={egg:I};
		h.getElementsByTagName(e)[0].parentNode.appendChild(D);
	})(window, document, "script", "https://aide.doorhan.ru/dhaide/js/dhaide.js",
	{
		type: "full",
		markup: 0,
		cityCode: "CB0000118",
		dealerCode: "IR0000992",
		agreementLink: "",
		
		
		
		layout: {
			aide: "dhaide",
		}
	});
</script>
<!--<![endif]-->

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>