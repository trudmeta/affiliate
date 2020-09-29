(function($){
    $(document).ready(function(){
        //$('.range').mask('0000-00-00');

        if ( $('.range_datepicker').length ) {
            $('.range_datepicker').datepicker({
                dateFormat : 'yy-mm-dd'
            });
        }

        if($('#chart').length == 0) {
            return;
        }
        var data;
        if (getQueryVariable('tab') == 'links-by-date' || !getQueryVariable('tab')) {
            data = tableData(af_links.af_links_data, 'title');
            getDonutChart(data);
        } else if (['link-by-date', 'link-cat-by-date'].indexOf( getQueryVariable('tab') ) !== -1 ) {
            getLineChart();
        } else if ( getQueryVariable('tab') == 'browser-by-date' ) {
            data = tableData(af_links.af_links_data, 'browser');
            getDonutChart(data);
        }
    });

    $('.rule-name').each(function() {
        $(this).change(function() {
            var self = this;
            var data = {
                'action': aLinkTargetUrl.action,
                'name': $(this).find('option:selected').val()
            };
            $.post(aLinkTargetUrl.ajax_url, data, function (response) {
                $(self)
                    .siblings('.rule-value')
                    .empty()
                    .append(response);
            });
        });
    });

    var tableData = function(links, title) {
        var data = [];
        for (var i=0; i < links.length; i++) {
            data[i] = [links[i][title], parseInt(links[i].hits)]
        }
        return data;
    };

    var chartColors = function(links) {
        var data = [];
        $.each(links, function(key, value) {
            data.push(value.legend);
        });
        return data;
    };

    var getDonutChart = function (data) {

        var plot = $.jqplot('chart', [data], {
            seriesColors: chartColors(af_links.af_links_data),
            seriesDefaults: {
                // make this a donut chart.
                renderer:$.jqplot.DonutRenderer,
                rendererOptions:{
                    // Donut's can be cut into slices like pies.
                    sliceMargin: 2,
                    // Pies and donuts can start at any arbitrary angle.
                    startAngle: -90,
                    showDataLabels: true,
                    // By default, data labels show the percentage of the donut/pie.
                    // You can show the data 'value' or data 'label' instead.
                    dataLabels: 'value',
                    // "totalLabel=true" uses the centre of the donut for the total amount
                    totalLabel: true
                }
            }
        });
        var temp = {
            grid: {
                backgroundColor: 'white',
                borderWidth: 0,
                shadow: false
            }
        };
        plot.themeEngine.newTheme('test', temp);
        plot.activateTheme('test');
    };

    var getLineChart = function() {
        var max = getMaxForAxis(af_links.af_links_data);
        var interval = Math.floor(max/5) < 1 ? 1 : Math.floor(max/5) + 1;
        var plot = $.jqplot ('chart', [af_links.af_links_data], {
            axesDefaults: {
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer
            },
            axes: {
                xaxis:{
                    renderer: $.jqplot.DateAxisRenderer,
                    tickOptions:{formatString:'%#d %b'},
                    tickInterval:'1 day'
                },
                yaxis: {
                    label: "Hits",
                    min: 0,
                    tickInterval: interval,
                    max: interval*6
                }
            }
        });

        var newTheme = plot.themeEngine.copy('Default', 'temp');
        newTheme.grid.backgroundColor = 'white';
        newTheme.grid.borderWidth = 0;
        newTheme.grid.shadow = false;
        plot.activateTheme('temp');
    };

    function getMaxForAxis (data) {
        var max = Math.max.apply(null, data.map(function(item) { return parseInt(item[1]); }));
        return Math.round(max);
    }

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) == variable) {
                return decodeURIComponent(pair[1]);
            }
        }
    }
    if ( $('#enable_ga:checked').length  ) {
        $('#af-link-form-table tr:not(:first-child)').show();
    }

    //toggle Google Analytics settings
    $('#enable_ga').change( function() {
        $('#af-link-form-table tr:not(:first-child)').toggle()
    } );

    //import-export
    $('.impexp-nav-tab').on('click', function(e){
        e.preventDefault();
        var $target = $(e.target);
        if($target.is('.nav-tab-active')) return;
        $('.impexp-nav-tab').removeClass('nav-tab-active');
        $target.addClass('nav-tab-active');
        var href = $target.attr('href');
        $('.nav-tab-item').removeClass('nav-tab-item-active');
        $(href).addClass('nav-tab-item-active');
    });

    //парсит url и возвращает объект location.search
    function getParsedUrlParams(indexof='post_type=affiliate-links'){
        var url = new URL(location.href);
        var parsedSearch = {};
        if(url.search != 'undefined' && url.search && url.search.indexOf(indexof)){
            var search = url.search.replace('?','');
            var searchArray = search.split('&');
            for(var key in searchArray){
                var getKeyValue = searchArray[key].split('=');
                parsedSearch[getKeyValue[0]] = getKeyValue[1];
            }
        }
        return parsedSearch;
    }
    var parsedUrl = getParsedUrlParams();
    if(parsedUrl.post_type == 'affiliate-links' && parsedUrl.tab == 'import' && parsedUrl.nav == 'history'){
        $('.impexp-nav-tab.affiliate_history').trigger('click');
    }

    //удаление сохранённого csv
    $('.js-history-delete').on('click',function(e){
        var $target = $(e.target);
        var file = $target.attr('data-file');
        if($target.is('input') && confirm("Удалить csv?")){
            if(file != ''){
                var data = {
                    action: 'af_delete_link',
                    file:file,
                };
                $.ajax({
                    type: "post",
                    url: aLinkTargetUrl.ajax_url,
                    data: data,
                    success: function (res) {
                        console.log(res);
                        if(res=='ok'){
                            $target.closest('p').remove();
                        }
                    },
                    error: function(err){
                        // console.log(err);
                    }
                });
            }
        }
    });

})(jQuery);