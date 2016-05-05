$(function () {
    $('#activeSeasonChartCombobox').change(function () {
        if( $('#activeSeasonChartCombobox').val() === 'plantsToVarietes'){
            $("#activeSeasonChart").load("activePlantsToVarietesChar.html");
        }
        if( $('#activeSeasonChartCombobox').val() === 'fieldDescriptionToPlants'){
            $("#activeSeasonChart").load("activeSeasonFieldDescriptionToPlantsChar.html");
        }
        if( $('#activeSeasonChartCombobox').val() === 'fieldNrToPlants'){
            $("#activeSeasonChart").load("activeSeasonFieldNrToPlantsChar.html");
        }
    });

});

