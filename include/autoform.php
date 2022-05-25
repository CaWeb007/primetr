<div id="autoFormTrigger" data-event="jqm" data-param-id="32" data-name="callback"></div>
<script>
    $(document).ready(function () {
        if (!$.cookie('was_perimetr24')) {
                setTimeout(function () {
                    $('#autoFormTrigger').trigger('click');
                }, 60000)
        }
        $.cookie('was_perimetr24', true, {
            expires: 1,
            path: '/'
        });
    });
</script>