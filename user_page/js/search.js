$(document).ready(function() {
    // Use event delegation to handle dynamically added elements
    $(document).on("keyup", "#live_search", function() {
        var input = $(this).val();
        
        if (input != "") {
            $.ajax({
                url: '../search.php',
                method: "POST",
                data: { input: input },
                success: function(data) {
                    // Assuming that your search.php returns HTML content
                    $("#search_result").html(data);
                    $("#search_result").css("display", "block"); // Show the results
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#search_result").css("display", "block");
                }
            });
        } else {
            $("#search_result").css("display", "none"); // Hide the results
        }
    });

    // Toggle the search result visibility
    $(document).on("click", function(event) {
        if (!$(event.target).closest("#search_result, #live_search").length) {
            // If the click is outside the search result or input, hide the search result
            $("#search_result").css("display", "none");
        }
    });

    // Click event for the bell
    $("#bell").click(function (event) {
        event.stopPropagation(); // Stop the click event from propagating to the document

        // Toggle the 'clicked' class on the bell icon
        $("#bell i").toggleClass("clicked");

        // If the bell is clicked, show or hide notifications
        if ($("#bell i").hasClass("clicked")) {
            fetchAndDisplayNotifications();
        } else {
            // Hide notifications dropdown
            $("#notifications").hide();
        }
    });

    // Function to fetch and display notifications
    function fetchAndDisplayNotifications() {
        $.ajax({
            url: '../user_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                displayNotifications(data);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Function to display notifications
    function displayNotifications(notifications) {
        // Update notification count
        $("#notification-count").text(notifications.length);

        // Clear existing notifications
        $("#notifications").empty();

        // Append new notifications
        notifications.forEach(function (notification) {
            var notificationItem = '<div class="notification-item">' +
                '<p>' + notification.message + '</p>' +
                '<small>' + notification.timestamp + '</small>' +
                '</div>';

            $("#notifications").append(notificationItem);
        });

        // Display notifications dropdown
        $("#notifications").show();
    }

    // Hide notifications dropdown when clicking outside
    $(document).click(function () {
        // Hide notifications dropdown
        $("#notifications").hide();
        // Remove the 'clicked' class from the bell icon
        $("#bell i").removeClass("clicked");
    });
});
