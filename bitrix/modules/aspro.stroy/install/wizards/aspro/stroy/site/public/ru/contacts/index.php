<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?>
<div class="row contacts" itemscope itemtype="http://schema.org/Organization">
	<div class="col-md-12">
		<div class="row contacts">
			<div class="col-md-5"> 
				<div itemprop="description"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-about.php", Array(), Array("MODE" => "html", "NAME" => "Contacts about"));?></div>
			</div>
			<div class="col-md-7"> 
				<table cellpadding="0" cellspasing="0"> 
					<tbody> 
						<tr>
							<td align="left" valign="top"><i class="fa colored fa-map-marker"></i></td><td align="left" valign="top"><span class="dark_table">Адрес</span>
								<br />
								<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
							</td>
							<td align="left" valign="top"><i class="fa colored fa-phone"></i></td><td align="left" valign="top"> <span class="dark_table">Телефон</span>
								<br />
								<span itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></span>
							</td>
						</tr>
						<tr>
						</tr>
						<tr>
							<td align="left" valign="top"><i class="fa colored fa-envelope"></i></td><td align="left" valign="top"> <span class="dark_table">E-mail</span>
								<br />
								<span itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?></span>
							</td>
							<td align="left" valign="top"><i class="fa colored fa-clock-o"></i></td><td align="left" valign="top"> <span class="dark_table">Режим работы</span>
								<br />
								<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", Array(), Array("MODE" => "html", "NAME" => "Schedule"));?>
							</td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

		</div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
	</div><?// class="maxwidth-theme?>
</div><?// class=row?>
<div class="map_block">
	<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	".default", 
	array(
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:55.76;s:10:\"google_lon\";d:37.639999999999986;s:12:\"google_scale\";i:10;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:7:\"COMPANY\";s:3:\"LON\";d:37.628173828125;s:3:\"LAT\";d:55.74566603524847;}}}",
		"MAP_WIDTH" => "100%",
		"MAP_HEIGHT" => "500",
		"CONTROLS" => array(
			0 => "SMALL_ZOOM_CONTROL",
		),
		"OPTIONS" => array(
			0 => "ENABLE_DRAGGING",
			1 => "ENABLE_KEYBOARD",
		),
		"MAP_ID" => "",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
	?>
</div>
<div class="color_block">
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<div class="block front">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-3 col-sm-3">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text1.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
										<div class="col-md-7 col-sm-6">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text2.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
										<div class="col-md-2 col-sm-3">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text3.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>