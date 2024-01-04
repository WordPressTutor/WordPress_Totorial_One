jQuery(document).ready(function($) {

    $('#sound-form').submit(function(e) {
        e.preventDefault();

        var soundData = $('#sound-upload').val();

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_save_sound_data',
                sound: [soundData],
            },
            success: function(response) {
                alert(response);
                $('#sound-upload').val('');
                fetchAndDisplaySoundData();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('#action-form').submit(function(e) {
        e.preventDefault();

        var displayData = $('#action').val();

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_save_action_data',
                display: [displayData],
            },
            success: function(response) {
                alert(response);
                $('#action').val('');
                fetchAndDisplayActionData();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('#pattern-form').submit(function(e) {
        e.preventDefault();

        var patternData = $('#pattern-input').val();

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_save_pattern_data',
                pattern: [patternData],
            },
            success: function(response) {
                alert(response);
                fetchAndDisplayPatternData();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    function fetchAndDisplaySoundData() {
        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_get_sound_data',
            },
            success: function(response) {
                displayDataInTable(response.data, '#sound-table');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function fetchAndDisplayActionData() {
        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_get_action_data',
            },
            success: function(response) {
                displayDataInTable(response.data, '#action-table');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function fetchAndDisplayPatternData(){
        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_get_pattern_data',
            },
            success: function(response) {
                displayDataInTable(response.data, '#pattern-table');
                console.log(response.data);
            },
            error: function(error) {
                console.log(error);
            }
        });

    }
    
    function displayDataInTable(data, tableId) {
        console.log(data);
        var tableBody = $(tableId + ' tbody');
    
        if (data.length > 0) {
            tableBody.empty();
    
            $.each(data, function(index, item) {
                var rowData, editButton, deleteButton;
                // Determine row data based on properties
                var rowData = item.hasOwnProperty('sound') ? item.sound : (item.hasOwnProperty('display') ? item.display : (item.hasOwnProperty('pattern') ? item.pattern : []));
                editButton = '<button type="button" class="btn btn-warning btn-sm" onclick="editRow(' + index + ')">Edit</button>';
                deleteButton = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteActionRow(' + index +', '+ item.id +')">Delete</button>';
                var rowHtml = '<tr><td>' + rowData.join(', ') + '</td><td>' + editButton + deleteButton + '</td></tr>';
                tableBody.append(rowHtml);
            });
        } else {
            // Display a message if there is no data
            tableBody.html('<tr><td colspan="2">No data available</td></tr>');
        }
        
    }
    
    fetchAndDisplaySoundData();
    fetchAndDisplayActionData();
    fetchAndDisplayPatternData();    
});

function deleteSoundRow(index) {
    if (confirm("Are you sure you want to delete this item?")) {
        jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_delete_sound_data',
                index: index,
            },
            success: function (response) {
                alert(response);
                fetchAndDisplaySoundData(); 
                fetchAndDisplayActionData();
                fetchAndDisplayPatternData();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
}

function deleteActionRow(index, itemId) {
    if (confirm("Are you sure you want to delete this item?")) {
        jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'csf_delete_action_data',
                index: index,
                id: itemId,
            },
            success: function (response) {
                alert(response);
                fetchAndDisplaySoundData(); 
                fetchAndDisplayActionData();
                fetchAndDisplayPatternData();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
}
