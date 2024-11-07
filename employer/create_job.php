<?php 

session_start();

include "../navbar.php"; 

$countries = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic of the", "Congo, Republic of the", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
);

include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $created_by_email = $_SESSION['email'];
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $joining_date = mysqli_real_escape_string($conn, $_POST['joining_date']);
    $required_skillset = mysqli_real_escape_string($conn, $_POST['required_skillset']);
    $rest_of_requirements = mysqli_real_escape_string($conn, $_POST['rest_of_requirements']);
    $lei = mysqli_real_escape_string($conn, $_POST['lei']);
    $lei_issuer = mysqli_real_escape_string($conn, $_POST['lei_issuer']);

    $result = mysqli_query($conn, "SELECT id FROM auth WHERE email = '$created_by_email' LIMIT 1");
    $created_by_id = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['id'] : null;

    if ($created_by_id) {
        $insert_query = "INSERT INTO jobs (created_by, company, role, location, salary, joining_date, required_skillset, rest_of_requirements, lei, lei_issuer)
                         VALUES ('$created_by_id', '$company', '$role', '$location', '$salary', '$joining_date', '$required_skillset', '$rest_of_requirements', '$lei', '$lei_issuer')";

        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['message'] = "Job posted successfully!";
            $_SESSION['status'] = 200;
            $_SESSION['success'] = true;
            $_SESSION['next'] = "/php/employer/jobs.php";
        } else {
            $_SESSION['message'] = "Error posting the job. Please try again later.";
            $_SESSION['status'] = 500;
            $_SESSION['success'] = false;
            $_SESSION['next'] = "/php/employer/create_job.php";
        }
    } else {
        $_SESSION['message'] = "User not found. Please log in again.";
        $_SESSION['status'] = 404;
        $_SESSION['success'] = false;
        $_SESSION['next'] = "/php/auth/login.php";
    }
    header("Location: /php/response.php");
    exit();
}

include "../disconnect.php";

?>



<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        .job-details {
            min-width: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .job-details form{
            padding: 20px 20px;
            margin-top: 100px;
            width: 50%;
        }

        .light-mode .job-details form{
            background-color: #e1e1e1; /* Light mode background */
            color: black; /* Text color for light mode */
        }

        .dark-mode .job-details form{
            background-color: #1e1e1e; /* Dark mode background */
            color: white; /* Text color for dark mode */
        }
    </style>
</head>
<div class="job-details">
    <form action="" method="post">
        <h3 class="text-center">Job Details</h3>
        <input name="created_by" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" hidden required>
        <input class="form-control mb-3" name="company" type="text" placeholder="Enter Company Name" required>
        <input class="form-control mb-3" name="role" type="text" placeholder="Enter Job Role" required>
        <input class="form-control mb-3" name="location" type="text" placeholder="Enter Work Location" required>
        <input class="form-control mb-3" name="salary" type="number" placeholder="Enter Minimum Salary" required>
        <input class="form-control mb-3" name="joining_date" type="date" placeholder="Enter Joining Date" required>
        <div class="d-flex mb-3">
            <input name="lei" type="text" class="form-control" maxlength="20" minlength="20" placeholder="Legal Entity Identifier (LEI)" required>
            <select class="form-control" name="lei_issuer" required>
                <option selected disabled value="">Select Company's LEI Issuer</option>
                <?php foreach ($countries as $key => $value): ?>
                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="d-flex" style="height:120px;">
            <textarea class="form-control mb-3 me-5" name="required_skillset" type="text" placeholder="Enter Required Skill(Comma Seperated)" required></textarea>
            <textarea class="form-control mb-3" name="rest_of_requirements" type="text" placeholder="Enter Remaining Requirements(Comma seperated)" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
</div>

<?php session_write_close(); ?>