init();
function init() {
    btnSubmitLoding();

    $("document").on("pjax:end", '.pjax-filter', function () {
        btnSubmitLoding();
    });

    $('input[type="number"]').on('keyup change paste input', function (e) {
        this.value = this.value < 0 ? 0 : this.value;
    });

    $('main').on('click', '.modalButton', function () {
        $('.modal-title').html($(this).attr('modal-title'));
        $('#modal').modal('show')
            .find('#modalContent')
            .html('Loading...')
            .load($(this).attr('value'));
    });

    $('#dynamic-form').on('keydown', 'input, select', function (e) {
        return disableEnter(e);
    });

    window.setInterval(function () {
        $('select, input, textarea').removeClass('is-valid');
    }, 1000);
}

function toDecimal(selector, value) {
    $(selector).val(toFixed(value));
}

function toIntDecimalNumber(value) {
    if (value.length > 0) {
        return parseFloat(value.replace('.', '').replace(',', '.'));
    }
    return value;
}

function toFixed(value, number = 2) {
    return value.toFixed(number);
}

function btnLoading(target) {
    var tmp = target.html();
    target.html('<span class="fa fa-spin fa-spinner"></span> Loading ...');
    target.prop('disabled', true);
    return tmp;
}

function btnSubmitLoding() {
    $('body').on('beforeSubmit', 'form', function () {
        btnLoading($(this).find('button[type="submit"]'));
    });
}

function toCurrency(val, locale = 'id-ID', isPlain = false) {
    if (isPlain) {
        return val.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, '$1.');
    }

    const formatter = new Intl.NumberFormat(locale);

    return formatter.format(val);
}

function formatDate(date = new Date()) {
    let d = new Date(date);
    let month = (d.getMonth() + 1).toString();
    let day = d.getDate().toString();
    let year = d.getFullYear();

    if (month.length < 2) {
        month = '0' + month;
    }

    if (day.length < 2) {
        day = '0' + day;
    }

    return [year, month, day].join('-');
}

function degToRad(degrees) {
    return degrees * (Math.PI / 180);
}