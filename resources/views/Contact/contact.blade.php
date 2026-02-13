<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: "Heebo", sans-serif;
}

body {
    margin: 0;
    font-size: 16px;
    background-color: #ffffff;
}

/* Top Section */
.header-section {
    background: linear-gradient(115deg, rgba(102,125,255,1) 0%, rgba(122,140,255,1) 89%);
    padding: 80px 20px 120px;
    text-align: center;
    color: white;
}

.header-section h1 {
    margin: 0;
    font-size: 48px;
}

.header-section p {
    margin-top: 10px;
    font-weight: 400;
}

/* Form Card */
.form-wrapper {
    max-width: 750px;
    margin: -80px auto 60px;
    padding: 0 20px;
}

.container {
    background-color: #f2f2f2;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* Form Layout */
form {
    width: 100%;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-row > div {
    flex: 1;
}

/* Inputs */
label {
    font-weight: 500;
    display: block;
    margin-bottom: 6px;
}

input[type=text],
input[type=email],
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d0d0d0;
    border-radius: 6px;
    font-size: 15px;
    margin-bottom: 20px;
    transition: 0.3s ease;
}

input:focus,
textarea:focus {
    border-color: #667dff;
    box-shadow: 0 0 0 2px rgba(102,125,255,0.2);
    outline: none;
}

textarea {
    resize: none;
    height: 150px;
}

/* Button */
input[type=submit] {
    background-color: #121331;
    color: white;
    padding: 12px 28px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s ease;
}

input[type=submit]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 600px) {
    .form-row {
        flex-direction: column;
    }

    .header-section h1 {
        font-size: 32px;
    }

    .container {
        padding: 25px;
    }
}
</style>
</head>

<body>

<div class="header-section">
    <h1>Contact Us</h1>
</div>

<div class="form-wrapper">
    <div class="container">
        <form enctype="multipart/form-data">
            <input type="hidden" name="_token" value="xXUwz1tq0mdpa27upFsvsbfVKy9MASgs9eeAWxZG">

            <div class="form-row">
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Your Name" required>
                </div>

                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Your Email" required>
                </div>
            </div>

            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" placeholder="Subject" required>

            <label for="message">Message</label>
            <textarea name="message" id="message" placeholder="Leave a message here" required></textarea>

            <input type="submit" value="Send Message">
        </form>
    </div>
</div>

</body>
</html>
