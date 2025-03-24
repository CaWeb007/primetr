const fortuneWheel = function (options){
    this.prize = options.PRIZE
    this.rotate = options.ROTATE
    this.status = options.STATUS
    this.timer = options.TIMER
    this.phone = null;
    this.component = $('#fortune_wheel_component')

    this.openButton = this.component.find('.fortune-wheel-open-button')
    this.closeButton = this.component.find('.fortune-wheel-modal-container-close-button')

    this.modal = this.component.find('.fortune-wheel-modal')
    this.insideModal = this.component.find('.fortune-wheel-modal-container')

    this.formStart = this.component.find('.fw-form-container-start')
    this.formStartInputContainer = this.formStart.find('.fw-form-container-input-container')
    this.formStartInputContainerInput = this.formStartInputContainer.find('input')
    this.formStartInputContainerSuccessIcon = this.formStartInputContainer.find('.fw-form-container-input-container-success-icon')
    this.formStartInputContainerErrorIcon = this.formStartInputContainer.find('.fw-form-container-input-container-error-icon')
    this.formStartActionButton = this.formStart.find('.fw-form-container-button-container > button')

    this.formEnd = this.component.find('.fw-form-container-end')
    this.formEndPrizeName = this.formEnd.find('.fw-form-container-prize')
    this.formEndCloseButton = this.formEnd.find('.fw-form-container-button-container > button')

    this.wheel = this.component.find('.fw-wheel')
    this.triangle = this.component.find('.fw-wheel-triangle')
    this.JWindow = $(window)

    this.prizeAction = function () {
        BX.ajax.runComponentAction(
            'custom:fortune.wheel',
            'saveResult',
            {
                mode: 'class',
                data: {
                    phone: this.phone,
                    prize: this.prize,
                    iblockId: options.RESULT_IBLOCK_ID
                }
            }
        ).then(
            response => {
                if (response.data.success) {
                    this.formEndPrizeName.text(this.prize)
                    this.formStart.remove()
                    this.formEnd.css('display', 'flex')
                } else {
                    this.component.remove()
                    console.log(response)
                    alert("Неизвестная ошибка, попробуйте перезагрузить страницу.")
                }
            },
            response => {
                this.component.remove()
                console.log(response)
                alert("Неизвестная ошибка, попробуйте перезагрузить страницу.")
            }
        )
    }
    this.spin = function (){
        this.wheel.css('transform', `rotate(${this.rotate}deg)`)
        setTimeout($.proxy(this.prizeAction, this), this.timer)
    }
    this.formActionButtonHandler = function () {
        this.formStartInputContainer.addClass('disable')
        this.formStartInputContainerInput.prop('disabled', true)
        this.formStartActionButton.prop('disabled', true)
        this.spin()
    }
    this.inputMaskCompleteHandler = function () {
        this.phone = this.formStartInputContainerInput.val()
        this.formStartActionButton.prop('disabled', false)
        this.formStartInputContainer.removeClass('error')
        this.formStartInputContainerSuccessIcon.show()
        this.formStartInputContainerErrorIcon.hide()
    }
    this.inputMaskInCompleteHandler = function () {
        this.phone = null
        this.formStartActionButton.prop('disabled', true)
        this.formStartInputContainerSuccessIcon.hide()
        this.formStartInputContainerErrorIcon.show()
        this.formStartInputContainer.addClass('error')
    }
    this.openModal = function (){
        if (this.modal.hasClass('fortune-wheel-modal-opened')) return false
        this.modal.addClass('fortune-wheel-modal-opened')
    }
    this.closeModal = function (){
        if (!this.modal.hasClass('fortune-wheel-modal-opened')) return false
        this.modal.removeClass('fortune-wheel-modal-opened')
    }
    this.closeModalAnywhere = function (event) {
        if (this.openButton.is(event.target)
            || this.openButton.has(event.target).length !== 0) return false;
        if (this.insideModal.is(event.target)
            || this.insideModal.has(event.target).length !== 0) return false;
        this.closeModal()
    }
    this.addModalListeners = function () {
        this.openButton.on('click', $.proxy(this.openModal, this))
        this.closeButton.on('click', $.proxy(this.closeModal, this))
        this.formEndCloseButton.on('click', $.proxy(this.closeModal, this))
        this.JWindow.on('click', $.proxy(this.closeModalAnywhere, this))
    }
    this.init = function (){
        this.addModalListeners()
        if (this.status === 'END') return true

        this.formStartInputContainerInput.inputmask({
            mask: '+7 (999) 999-99-99',
            oncomplete: $.proxy(this.inputMaskCompleteHandler, this),
            onincomplete: $.proxy(this.inputMaskInCompleteHandler, this)
        })
        this.formStartActionButton.on('click', $.proxy(this.formActionButtonHandler, this))

    }
    this.init()
}