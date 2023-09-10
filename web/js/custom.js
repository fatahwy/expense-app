init();
function init() {
    $('[data-toggle="tooltip"]').tooltip();

    btnSubmitLoding();

    $("document").on("pjax:end", '.pjax-filter', function () {
        btnSubmitLoding();
    });

    $('input[type="number"]').on('keyup change paste input', function (e) {
        this.value = this.value < 0 ? 0 : this.value;
    });

    $('main').on('click', '.modalButton', function() {
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

function toCurrency(val, isPlain = false) {
    if (isPlain) {
        return val.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, '$1.');
    }

    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        // These options are needed to round to whole numbers if that's what you want.
        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    });

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

const plugins = {
    id: 'custom_canvas_background_color',
    beforeDraw: (chart, args, options) => {
        const { ctx } = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = "rgba(255, 255, 255)";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

function createChart(labels, datasets, type = 'line', selector = 'myChart') {
    const ctx = document.querySelector('.' + selector).getContext('2d');

    const myChart = new Chart(ctx, {
        type: type,
        data: {
            labels,
            datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        footer: (ttItem) => {
                            if (type === 'pie') {
                                let sum = 0;
                                let dataArr = ttItem[0].dataset.data;
                                dataArr.map(data => {
                                    sum += Number(data);
                                });

                                let percentage = (ttItem[0].parsed * 100 / sum).toFixed(2) + '%';
                                return `Persentase : ${percentage}`;
                            }
                        }
                    }
                },
            }
        },
        plugins: [plugins]
    });
}

function degToRad(degrees) {
    return degrees * (Math.PI / 180);
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371000; // Radius Bumi dalam meter

    const dLat = degToRad(lat2 - lat1);
    const dLon = degToRad(lon2 - lon1);

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(degToRad(lat1)) * Math.cos(degToRad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = earthRadius * c;
    return distance;
}