<!DOCTYPE html>
<html>
<head>
    <title>Seat Booking App</title>
</head>
<body>
<h1>Enter number of tickets required by you</h1>
<form method="POST" action="" onsubmit="return validateForm()">
    @csrf
    <label for="numSeats">Number of Seats:</label>
    <input type="number" name="numSeats" id="numSeats" required>
    <button type="submit">Book Seats</button>
</form>

<script>
    function validateForm() {
        var numSeatsInput = document.getElementById('numSeats');
        var numSeats = parseInt(numSeatsInput.value);

        if (numSeats > 7 || numSeats < 1 ) {
            alert('Number of seats should not be greater than 7 oe less than 1');
            numSeatsInput.value = '';
            numSeatsInput.focus();
            return false;
        }

        // Dynamically set the form action based on the validation result
        var form = document.querySelector('form');
        if (numSeats <= 7 && numSeats >= 1) {
            form.action = '/book';
        } else {
            form.action = ''; // Set it to an appropriate URL or leave it empty if you don't want to submit the form
        }

        return true;
    }
</script>
</body>
</html>
