const fortuneWheel = function (options){
    this.prizes = options.PRIZES
    this.fortuneCookie = options.COOKIE_PRIZE
    this.currentPrize = {name: this.fortuneCookie.PRIZE}
    this.container = $('#fortune-wheel-component')

    this.modal = this.container.find('#modal')
    this.button = this.container.find('.open-modal-button')
    this.modalContent = this.container.find('.modal-content')

    this.buttonClose = this.modalContent.find('.close-button')
    this.currentPrizeContainer = this.modalContent.find('.currentPrizeContainer')
    this.wheelContainer = this.modalContent.find('.wheel-container')
    this.phoneContainer = this.modalContent.find('.phone-input-container')
    this.thanksText = this.modalContent.find('.thanks-text')

    this.currentPrizeSpan = this.currentPrizeContainer.find('.currentPrize')

    this.wheel = this.wheelContainer.find('#wheel')
    this.buttonSpin = this.wheelContainer.find('.spin-button')

    this.phone = this.phoneContainer.find('#phone')
    this.buttonSubmit = this.phoneContainer.find('.submit')

    this.JWindow = $(window)

    this.openModal = function (){
        if (this.modal.hasClass('modal-opened')) return false
        this.modal.addClass('modal-opened')
    }
    this.closeModal = function (){
        if (!this.modal.hasClass('modal-opened')) return false
        this.modal.removeClass('modal-opened')
    }
    this.closeModalAnywhere = function (event) {
        if (this.button.is(event.target)) return false;
        if (this.modalContent.is(event.target)
            || this.modalContent.has(event.target).length !== 0) return false;
        this.closeModal()
    }
    this.submitHandler = function (){
        const phone = this.phone.val()
        if (!phone) return
        BX.ajax.runComponentAction(
            'custom:fortune.wheel',
            'saveResult',
            {
                mode: 'class',
                data: {
                    phone: phone,
                    prize: this.currentPrize.name,
                    iblockId: options.RESULT_IBLOCK_ID
                }
            }
        ).then(response => {
            if (response.data.success) {
                this.phoneContainer.remove()
                this.thanksText.show()
            } else {
                alert("Неизвестная ошибка, попробуйте позже.")
            }
        });
    }
    this.spinHandler = function (){
        const randomDegree = Math.floor(Math.random() * 360) + 1440
        this.wheel.css('transform', `rotate(${randomDegree}deg)`)
        setTimeout($.proxy(this.prizeAction, this), 5000)
    }
    this.getRandomPrize = function () {
        const totalProbability = this.prizes.reduce((sum, prize) => sum + prize.probability, 0)
        const randomValue = Math.random() * totalProbability

        let cumulativeProbability = 0;
        for (const prize of this.prizes) {
            cumulativeProbability += prize.probability
            if (randomValue <= cumulativeProbability) {
                return prize
            }
        }
    }
    this.setPrizeInCookie = function () {
        BX.ajax.runComponentAction(
            'custom:fortune.wheel',
            'saveStatus',
            {
                mode: 'class',
                data: {
                    prize: this.currentPrize.name
                }
            }
        )
    }
    this.prizeAction = function () {
        this.currentPrize = getRandomPrize()
        this.setPrizeInCookie()
        this.wheelContainer.remove()
        this.currentPrizeSpan.text(this.currentPrize.name)
        this.currentPrizeContainer.show()
        this.phoneContainer.show()
    }
    this.addListeners = function () {
        this.button.on('click', $.proxy(this.openModal, this))
        this.buttonClose.on('click', $.proxy(this.closeModal, this))
        this.JWindow.on('click', $.proxy(this.closeModalAnywhere, this))
        if (!this.currentPrize.name)
            this.buttonSpin.on('click', $.proxy(this.spinHandler, this))
        if (this.fortuneCookie.STATUS !== 'END')
            this.buttonSubmit.on('click', $.proxy(this.submitHandler, this))
    }
    this.init = function (){
        this.addListeners()
    }
    this.init()
}