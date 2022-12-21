<div class="topBannerWrapper" id="topBannerWrapper" style="display: none;">
    <a class="desktopBanner" style="height: 50px"></a>
    <a class="mobileBanner" style="height: 70px"></a>
    <div class="closeBannerWrap">
        <div class="closeBanner">
            <div class="closeIcon">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="popoverBanner">Скрыть баннер</div>
    </div>
</div>
<script>
    window.JCIblockVoteStars = function (active){
        this.wrap = $('#topBannerWrapper');
        this.desktopBanner = this.wrap.find('.desktopBanner');
        this.mobileBanner = this.wrap.find('.mobileBanner');
        this.closeBanner = this.wrap.find('.closeBanner');
        this.cookieName = 'BITRIX_PM24_TBW_CLOSED';
        this.showBanner = !$.cookie(this.cookieName);
        this.active = (active === 'Y');
        this.init = function () {
            if (!this.showBanner || !this.active) return;
            this.showBannerAction();
            this.closeBanner.on('click', $.proxy(this.closeBannerAction, this))
        }
        this.showBannerAction = function (){
            this.wrap.show();
            $.getJSON( "/ajax/top_banner.php", $.proxy(this.ajaxSuccess, this));
        }
        this.ajaxSuccess = function (data){
            if (!data.desktopBanner && !data.background && !data.mobileBanner && !data.link){
                this.wrap.hide();
                return;
            }
            if (data.desktopBanner){
                this.desktopBanner.append(
                    $('<img>', {
                        src: data.desktopBanner
                    })
                );
            }
            if (data.background)
                this.wrap.css('background-color', data.background)
            if (data.mobileBanner){
                this.mobileBanner.append(
                    $('<img>', {
                        src: data.mobileBanner
                    })
                );
            }
            if (data.link){
                this.desktopBanner.attr({
                    'href': data.link,
                    'style': ""
                })
                this.mobileBanner.attr({
                    'href': data.link,
                    'style': ""
                })
            }
        }
        this.closeBannerAction = function (event){
            var confirm = this.confirm('Вы уверены?');
            if (!confirm) return;
            $.cookie(this.cookieName, true, {
                expires: 1,
                path: '/'
            });
            this.wrap.hide();
        }
        this.init();
    }
    window.JCIblockVoteStars('<?=$active?>');
</script>