$(document).ready(function() {
    $(document).ready(function() {
        function filterAutocompleteData(query) {
            return autocompleteData.filter(function(item) {
                return item.toLowerCase().indexOf(query.toLowerCase()) > -1;
            });
        }

        function showAutocompleteSuggestions(inputElement, suggestions) {
            const $dropdown = $(`#${inputElement.attr('id')}-dropdown`);
            $dropdown.empty();

            suggestions.forEach(function(suggestion) {
                $dropdown.append(`<div class="dropdown-item">${suggestion}</div>`);
            });

            $dropdown.show();
        }

        $('.form-control').on('input', function() {
            var query = $(this).val();
            var dataAction = $(this).data("action");
            var $dropdown = $(this).closest(".col").find(".autocomplete-dropdown");
            var dataCity = "";
            if(dataAction == 'find_stop') {
                console.log("INSIDE");
                dataCity = $("#city").val();
            }

            if(query.length >= 3) {
                $.ajax({
                    url: "functions.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        action: dataAction,
                        search: query,
                        city: dataCity
                    },
                    success: function(data) {
                        $dropdown.empty();

                        $.each(data, function(index, item) {
                            $dropdown.append("<div class='dropdown-item' data-id='" + index + "'>" + item + "</div>");
                        });
                        $dropdown.show();
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            } else {
                $(".autocomplete-dropdown").hide();
            }
        });

        $('#find_bus').on('click', function() {
            var $bus_list = $("#bus_list");
            var dataAction = "find_bus";
            var stopName = $("#busstop").val();

            if($("#busstop").val()) {
                $.ajax({
                    url: "functions.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        action: dataAction,
                        stop_name: stopName
                    },
                    success: function(data) {
                        $bus_list.empty();

                        $.each(data, function(index, item) {
                            $bus_list.append("<button type=\"button\" class=\"btn btn-primary btn-sm js-select-bus\">" + item + "</button>&nbsp;");
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            }
        });

        $('#find_closest').on('click', function() {
            var dataAction = "find_closest";
            var areaArea = $("#city").val();
            var stopName = $("#busstop").val();

            if($("#busstop").val()) {
                $.ajax({
                    url: "functions.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        action: dataAction,
                        stop_name: stopName,
                        stop_area: areaArea
                    },
                    success: function(data) {
                        $(".js-times").empty();

                        $.each(data, function(index, item) {
                            $(".js-times").append("<button type=\"button\" class=\"btn btn-sm\">" + item + "</button>&nbsp;");
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            }
        });

        $(document).on('click', ".js-select-bus", function(e) {
            var dataAction = "find_times";
            var busName = $(this).text();
            var stopName = $("#busstop").val();

            $.ajax({
                url: "functions.php",
                type: "GET",
                dataType: "json",
                data: {
                    action: dataAction,
                    bus_name: busName
                },
                success: function(data) {
                    console.log(data);
                    $(".js-times").empty();
                    $(".js-times").append("Lähim saabumisaeg: ");
                    $.each(data, function(index, item) {
                        $(".js-times").append("<button type=\"button\" class=\"btn btn-sm\">" + item + "</button>&nbsp;");
                    });
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + error);
                }
            });
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.form-control').length) {
                $('.autocomplete-dropdown').hide();
            }
        });

        $(document).on('click', '.dropdown-item', function() {
            const value = $(this).text();
            const inputId = $(this).parent().attr('id').replace('-dropdown', '');
            $(this).closest(".col").find(".form-control").val(value);
            $('.autocomplete-dropdown').hide();
        });
    });
});