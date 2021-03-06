/**
 * Handlers
 */
var modalButtonHandlers = function() {
    // save tags button
    $('#saveTagTrigger').click(function() {
        saveImageTags($("#imageId").html(), $("#imageTags").val());
    });

    // video accept/deny buttons
    $('#fab-modal-accept-button').click(function() {
        $('#fab-modal-accept-button').hide();
        $('#fab-modal-deny-button').show();
    });

    $('#fab-modal-deny-button').click(function() {
        $('#fab-modal-deny-button').hide();
        $('#fab-modal-accept-button').show();
    });

    $('#btnRotateImageLeft').click(function() {
        $(this).prop('disabled', true);
        rotateImage($(this), $("#imageId").html(), 'left');

    });

    $('#btnRotateImageRight').click(function() {
        $(this).prop('disabled', true);
        rotateImage($(this), $("#imageId").html(), 'right');
    });
}


var tabHandlers = function() {
    $('#modalTabs').tabs();
    $("#tabHistoryTrigger").click(function(e) {
        $("#tab-history").load("/adminImage/imageModalHistory/" + $("#imageId").html(), function(response, status, xhr) {
            if (status === "error") {
                var msg = "Sorry but there was an error loading history: ";
                alert(msg + xhr.status + " " + xhr.statusText);
            }
        });
    });
}

var tagHandlers = function() {
    //var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];
    $('#imageTags').tagit({
        //availableTags: sampleTags
    });
}

$(document).ready(function() {

    modalButtonHandlers();
    tabHandlers();
    tagHandlers();
    socialHandlers();
    imageStatusHandlers();
});

/**
 * Takes an image id and list of tags to save
 */
function saveImageTags(imageId, tags) {

    var request = $.ajax({
        url: '/adminImage/ajaxImageAddTags',
        type: 'POST',
        data: ({
            'tags': tags,
            'imageId': imageId,
            'CSRF_TOKEN': getCsrfToken()
        }),
        success: function(data) {
            alert('Image tags were saved.');
        },
        error: function(data) {
            alert('Unable to save image tags.');
            return false;
        }
    });
}


function rotateImage(buttonObj, imageId, direction) {

    $("#modalImage").attr("src", '/core/webassets/images/loading.gif');
    var request = $.ajax({
        url: '/adminImage/ajaxRotateImage',
        type: 'POST',
        dataType: 'json',
        data: ({
            'direction': direction,
            'imageId': imageId,
            'CSRF_TOKEN': getCsrfToken()
        }),
        success: function(data) {
            if (data.success == 'true') {
                $("#" + thumbnailId).attr("src", data.filename);
                $("#modalImage").attr("src", data.filename);
            } else {
                alert(data.message);
            }
            $(buttonObj).prop('disabled', false);
        },
        error: function(data) {
            alert(data.message);
            $(buttonObj).prop('disabled', false);
            return false;
        }
    });
}
