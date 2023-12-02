$(document).ready(function () {
    var today = new Date();

    $('#datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            console.log('Date selected:', dateText);
            // Note: Removed the immediate updateSelectedDate call here
        }
    });

    // Add click event for the Confirm Date button
    $('#confirmDate').click(function () {
        var selectedDate = $('#datepicker').val();
        updateSelectedDate(selectedDate);
    });

    function updateSelectedDate(selectedDate) {
        // AJAX request to send the selected date to the server
        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: {
                selectedDate: selectedDate
            },
            success: function (response) {
                console.log('Server response:', response);
                // Clear existing content including datepicker before updating
                $('#selected-date').html(response).find('#datepicker').datepicker('destroy');
                // Update the selected-date div with the server response
                $('#selected-date').html(response);
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }
    

    // Plus button click
    $('.plus').on('click', function () {
        updateQuantity($(this), 1);
    });

    // Minus button click
    $('.minus').on('click', function () {
        updateQuantity($(this), -1);
    });

    // Quantity input change
    $('.quantity-input').on('input', function () {
        updateQuantity($(this), 0);
    });

    function updateQuantity(element, change) {
        var reservationId = element.data('reservation-id');
        var quantityInput = element.siblings('.quantity-input');
        var newQuantity = Math.max(parseInt(quantityInput.val()) + change, 1);

        // Update the quantity on the page
        quantityInput.val(newQuantity);

        // Send AJAX request to update the server-side data
        $.ajax({
            type: 'POST',
            url: 'update_quantity.php',
            data: {
                reservationId: reservationId,
                newQuantity: newQuantity
            },
            success: function (response) {
                // Handle the server response if needed
                console.log(response);
            },
            error: function (error) {
                console.error('Error updating quantity:', error);
            }
        });
    }

    $('#select-all').on('change', function () {
        $('input[name="facility[]"]').prop('checked', this.checked);
        $('input[name="equipment[]"]').prop('checked', this.checked);
    });

    // Individual checkboxes
    $('input[name="facility[]"], input[name="equipment[]"]').on('change', function () {
        $('#select-all').prop('checked', $('input[name="facility[]"]:checked, input[name="equipment[]"]:checked').length === $('input[name="facility[]"], input[name="equipment[]"]').length);
    });

    // Delete button click
    $('.delete').on('click', function () {
        // Show the modal
        $('#deleteModal').show();
        $('.modal-overlay').show();

        var reservationId = $(this).data('reservation-id');
        $('#confirmDelete').data('reservation-id', reservationId);
    });


    $('#delete_solo').on('click', function () {
        // Show the modal
        $('#deleteModal').show();
        $('.modal-overlay').show();

        var reservationId = $(this).data('reservation-id');
        $('#confirmDelete').data('reservation-id', reservationId);
    });

    $('#submit').on('click', function () {
        console.log('Button Clicked'); // Add this line for debugging
        var selectedItems = [];
        var selectedDate = $('#datepicker').val();
    
        $('input[name="facility[]"]:checked, input[name="equipment[]"]:checked').each(function () {
            var itemId = $(this).val();
            selectedItems.push(itemId);
        });

        // console.log('Selected Date:', selectedDate);
        // console.log('Selected Items:', selectedItems);

    
        if (selectedItems.length > 0) {
            $('.modal-overlay').show();
            // Append the selected date to the URL
            window.location.href = 'checkout.php?items=' + selectedItems.join(',') + '&selectedDate=' + selectedDate;
        } else {
            alert('Please select items to check out.');
        }
    });
    $('#confirmDelete').on('click', function () {
        var reservationId = $(this).data('reservation-id');

        $.ajax({
            type: 'POST',
            url: 'delete_list_item.php',
            data: { reservationId: reservationId },
            success: function (response) {
                console.log(response);
                location.reload();
            },
            error: function (error) {
                console.error('Error deleting item:', error);
            }
        });

        $('#deleteModal').hide();
        $('.modal-overlay').hide();
    });

    $('#cancelDelete').on('click', function () {
        $('#deleteModal').hide();
        $('.modal-overlay').hide();
    });

    $('#delete_selected').on('click', function () {
        $('#deleteModal').show();
        $('.modal-overlay').show();
    });

    $('#confirmDelete').on('click', function () {
        var selectedItems = [];

        $('input[name="facility[]"]:checked, input[name="equipment[]"]:checked').each(function () {
            var itemId = $(this).val();
            selectedItems.push(itemId);
        });

        $.ajax({
            type: 'POST',
            url: 'delete_list_item.php',
            data: { selectedItems: selectedItems },
            success: function (response) {
                console.log(response);
                location.reload();
            },
            error: function (error) {
                console.error('Error deleting items:', error);
            }
        });

        $('#deleteModal').hide();
        $('.modal-overlay').hide();
    });
});
