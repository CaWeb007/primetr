const CawebMapStore = {
    init: function (options){
        this.initOptions = options
        this.stores = options.stores
        this.map = $('#' + options.mapId)
        this.triggers = $("#" + options.triggerWrapId).children('#' + options.triggerItemId)
        this.firstTrigger = $(this.triggers[0]);


        this.mapOptions = {
            map: {
                center: [this.firstTrigger.attr("data-gpsn"), this.firstTrigger.attr("data-gpss")],
                zoom: 12
            }
        }
        ymaps.ready($.proxy(this.initMap, this));
    },
    initMap: function () {
        this.ymaps = new ymaps.Map(this.initOptions.mapId, this.mapOptions.map)
        this.setPlacemark()
        this.triggers.on('click', $.proxy(this.triggersClickHandler, this))
    },
    triggersClickHandler: function (event){
        const target = $(event.currentTarget)
        let placemark = this.placemark[target.attr("data-storeId")]
        if (target.hasClass('selected')) return
        this.triggers.removeClass('selected')
        target.addClass('selected')
        this.ymaps.setCenter([target.attr("data-gpsn"), target.attr("data-gpss")])
        placemark.balloon.open()
    },
    setPlacemark: function (){
        this.placemark = {}
        for(let item in this.stores){
            const store = this.stores.hasOwnProperty(item)? this.stores[item] : null
            if (store === null) continue
            if (!store.hasOwnProperty('GPS_N')) store.GPS_N = null
            if (!store.hasOwnProperty('GPS_S')) store.GPS_S = null
            if (!store.hasOwnProperty('STORE_TITLE')) store.STORE_TITLE = null
            if (!store.hasOwnProperty('ID')) store.ID = null
            if (!store.hasOwnProperty('PHONE')) store.PHONE = null
            if (!store.hasOwnProperty('EMAIL')) store.EMAIL = null
            if (!store.hasOwnProperty('SCHEDULE')) store.SCHEDULE = null
            if (!store.hasOwnProperty('EMAIL')) store.EMAIL = null
            if (!store.hasOwnProperty('DESCRIPTION')) store.DESCRIPTION = null
            const balloonContentBody = [
                "<div class='balloon-content-body'>",
                    "<div class='balloon-phone'>",
                        '<a href="tel:' + store.SCHEDULE + '">',
                            store.PHONE,
                        '</a>',
                    "</div>",
                    "<div class='balloon-schedule'>",
                        "<span>",
                            store.DESCRIPTION,
                        "</span>",
                    "</div>",
                    "<div class='balloon-email'>",
                        '<a href="mailto:' + store.EMAIL + '">',
                            store.EMAIL,
                        '</a>',
                    "</div>",
                "</div>",
            ].join('')
            const balloonFooter = [
                "<div class='balloon-footer-wrap'>",
                    "<div class='balloon-link'>",
                        '<a href="https://yandex.ru/maps/?rtext=~' + store.GPS_N + '%2C' + store.GPS_S + '" target="_blank">',
                            'Построить маршрут',
                            '<i class="fa fa-angle-right"></i>',
                        '</a>',
                    "</div>",
                "</div>",
            ].join('')
            const placemark = new ymaps.Placemark(
                [store.GPS_N, store.GPS_S],
                {
                    balloonContentBody: balloonContentBody,
                    balloonContentFooter: balloonFooter
                },
                {
                    hideIconOnBalloonOpen: false,
                    balloonOffset: [0, -40],
                    balloonCloseButton: false,
                    preset: 'islands#redDotIcon'
                }
            )
            this.placemark[store.ID] = placemark
            this.ymaps.geoObjects.add(placemark)
        }
        this.placemark[this.firstTrigger.attr('data-storeId')].balloon.open()

    }

}