<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
        }

        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

</head>
<body>

    <?php include("nav.php"); ?>

    <main class="form-signin w-100 m-auto">
        <form id="registerForm">
            <h1 class="h3 mb-3 fw-normal">Sign Up</h1>
            <div id="response" class="alert alert-danger" role="alert" style="display:none;"></div>
            <div class="form-floating">
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name">
                <label for="name">Your name</label>
            </div>
            <div class="form-floating">
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email">
                <label for="Email address">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                <label for="Password">Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-body-secondary">© 2024</p>
        </form>
    </main> 

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting the traditional way

                const formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val()
                }

                $.ajax({
                    url: '/register-ajax/api/registerUser.php',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json', // sending JSON data to the server 
                    success: function(response) {
                        console.log(response);
                        if (response.success === true) {
                            $('#response').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
                        } else {
                            $('#response').removeClass('alert-success').addClass('alert-danger').text(response.message).show();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('An error occurred: ' + textStatus);
                        // $('#response').text('An error occurred: ' + textStatus);
                    }
                });
            });
        });
    </script>

</body>
</html>