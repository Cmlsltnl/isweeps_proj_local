var createCounters = function() {
    $('input.counter').each(function(i, e) {
        $(e).parent().append($('<div></div>').attr({'id': 'counter_' + $(e).attr('id')}));
        $('#counter_' + $(e).attr('id')).text($(e).attr('maxlength') + ' characters remaining.');
        $(e).on('keyup', function() {
            updateCount(this)
        });
        $(e).on('blur', function() {
            adjustMaxLength(this, 'blur')
        });
        $(e).on('focus', function() {
            adjustMaxLength(this, 'focus')
        });
    });
}

var adjustMaxLength = function(el, event) {
    var classes = $(el).attr('class').split(/ /);
    $.each(classes, function(i, e) {
        if (e.match('linkTo')) {
            if (event == 'focus') {
                var maxlength = parseInt($('#' + e.replace(/linkTo/, '')).attr('maxlength')) + parseInt($(el).val().length);
                $('#' + e.replace(/linkTo/, '')).attr({'maxlength': maxlength});
            } else {
                var maxlength = $('#' + e.replace(/linkTo/, '')).attr('maxlength') - $(el).val().length;
                $('#' + e.replace(/linkTo/, '')).attr({'maxlength': maxlength});
                $('#' + e.replace(/linkTo/, '')).trigger('keyup');
            }
        }
    });
}

var updateCount = function(el) {
    var l = $(el).attr('maxlength') - $(el).val().length;
    $('#counter_' + $(el).attr('id')).css({'color': 'black'}).text(l + ' characters remaining.');
    if (l < 0) {
        $('#counter_' + $(el).attr('id')).css({'color': 'red'}).text('Over character limit!');
    }
}

var linkPopUp = function(){
    $('.linkPopUp').off('click');
    $('.linkPopUp').on('click',function(e){
        e.preventDefault();
        $("#xml_url").val('');
        $("#rss_url").val('');
        $("#tv_url").val('');
        $("#tv_url2").val('');
        $("#linksPopUpOverlay").overlay({
                mask: '#000',
                effect: 'default',
                top: 25,
                closeOnClick: true,
                closeOnEsc: true,
                fixed: true,
                oneInstance: true,
                api: true
            }).load();

        var id = $(this).attr('rel');
        var baseurl =$("#baseurl").val();
        $("#xml_url").val(baseurl+'/XML/voting/'+id);
        $("#xml_url_lb").html('<a target="_blank" style="color:#000;" href="'+baseurl+'/XML/voting/'+id+'">XML URL</a>');
        $("#rss_url").val( baseurl+'/XML/votingRSS/'+id );
        $("#rss_url_lb").html('<a target="_blank" style="color:#000;" href="'+baseurl+'/XML/votingRSS/'+id+'">RSS URL</a>');
        $("#tv_url").val( baseurl+'/preview/questionPoll?id='+id );
        $("#tv_url_lb").html('<a target="_blank" style="color:#000;" href="'+baseurl+'/preview/questionPoll?id='+id+'">TV URL</a>');
        $("#tv_url2").val( baseurl+'/preview/questionPoll2?id='+id );
        $("#tv_url2_lb").html('<a target="_blank" style="color:#000;" href="'+baseurl+'/preview/questionPoll2?id='+id+'">TV URL 2</a>');
    });

}

var colorPickers = function() {
    var colors = ['red', 'yellow', 'green', 'blue', 'violet', 'darkorange','blanchedalmond',"#600","#783f04","#7f6000"];
    for (i = 0; i < 10; i++) {
        $("#colorpickerField" + i).off('change');
        $("#colorpickerField" + i).on('change', function() {
            var colorVal = $(this).val();
            var target = $(this).attr('id').substr($(this).attr('id').length - 1);
            console.log(colorVal);
            $("#fab-chart" + target).fadeOut('fast', function() {
                $("#fab-chart" + target).removeClass();
                $("#fab-chart" + target).css({"background-color": '', "height":""}); 

                if(colorVal) {
                    $("#fab-chart" + target).addClass("fab-vote-" + colorVal);
                    $("#fab-chart" + target).css({"background-color": colorVal, "height":"48px"}); 
                }
                $("#fab-chart" + target).fadeIn('slow');
            });
        });
        var selCol = $('#colorpickerField' + i).val() ? $('#colorpickerField' + i).val() : colors[i];
        $('#colorpickerField' + i).spectrum({
            showPaletteOnly: false,
            showPalette: true,
            color: selCol,
            clickoutFiresChange: true,
            preferredFormat: "name",
            palette: [colors],
             showInput: true

        })
        if ($('#pollAnswer' + i).css('display') == "block") {
            $('#colorpickerField' + i).val(selCol).trigger('change');
            $('#answer' + i + 'Preview').html($('#ePollAnswer_' + i + '_answer').val());
        } else {
            $('#colorpickerField' + i).val('').trigger('change');
            $('#answer' + i + 'Preview').html('');
        }
    }
}

var showAnswers = function(num) {
    $(".pollAnswer").hide();
    $(".pollAnswer").find($('input')).each(function(i, e) {
        $(e).attr({'disabled': 'disabled'});
    });
    var i = 0;
    for (i = 0; i < num; i++) {
        $("#pollAnswer" + i).show('fast');
        $("#pollAnswer" + i).find($('input')).each(function(i, e) {
            $(e).removeAttr('disabled');
        });
    }
    colorPickers();
}

var pollHandlers = function() {
    $('.setPollState').off('click');
    $('.setPollState').on('click', function(e) {
        e.preventDefault();
        var obj = new Object;
        obj.column = 'end_time';
        obj.value = $(this).attr('rev');
        obj.id = $(this).attr('rel');
        obj.CSRF_TOKEN = getCsrfToken();
        if (confirm('Are you sure you wish to make this edit?')) {
            var request = $.ajax({
                url: "/adminVoting/ajaxSetPollState",
                type: 'POST',
                data: $.param(obj),
                success: function(data) {
                	window.location=window.location;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                    alert("param:" + JSON.stringify(obj));
                }
            });
        }
    });


    $('input[type=text]').off('keyup');
    $('input[type=text]').on('keyup', function(i, e) {
        if ($(this).attr('id').indexOf('answer') != -1) {
            var target = $(this).parents('.pollAnswer').attr('id').substr($(this).parents('.pollAnswer').attr('id').length - 1);
            $('#answer' + target + 'Preview').html($(this).val());
        }
        if ($(this).attr('id').indexOf('question') != -1) {
            $('#questionPreview').html($(this).val());
        }
    });

    colorPickers();

    // type of poll selector
    /* $('.fab-chk').off('click');
    $('.fab-chk').on('click', function(e) {
        $('#fab-voting').find('.fab-poll').removeClass('fab-blue');
        if ($(this).is(":checked")) {
            showAnswers($(this).val());
            $('#fab-voting').find('input:checkbox').not($(this)).removeAttr('checked');
            $('#fab-voting').find('.fab-poll').removeClass('fab-blue');
            $(this).parent().parent().find('.fab-poll').addClass('fab-blue');
        }
    });

   */
    // type of poll selector
    $('.fab-bars').off('change');
    $('.fab-bars').on('change', function(e) {
        $('#fab-voting').find('.fab-poll').removeClass('fab-blue');
        if ($(this).val()) { 
            showAnswers($(this).val()); 
        }
    });


}

var dateTimeHandlers = function() {
    $('.ui-timepicker-input').datetimepicker({dateFormat: 'yy-mm-dd', timeFormat: 'HH:mm'});
};

$(document).ready(function() {
    pollHandlers();
    createCounters();
    dateTimeHandlers();
    linkPopUp();

    $('#votingTable').dataTable({
        "aaSorting": [[0, "desc"]]
    });
});

