jQuery(document).ready(function() {
    $('#search_calibration_charge').keydown(function(event) {
        if (event.keyCode == 13) {
            var baseUrl = "{{URL::to('/')}}";
            var params = {
                search:document.getElementById("search_calibration_charge").value
            };
            document.location.href = 'CalibrationChargeclient?'+jQuery.param(params);
        }
    });
});

function filter(){
    var baseUrl = "{{URL::to('/')}}";
    var params = {
        search:document.getElementById("search_calibration_charge").value
    };
    document.location.href = 'CalibrationChargeclient?'+jQuery.param(params);
}