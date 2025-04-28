function validateForm() {
  var isValid = true;

  // Reset all error messages
  var errorElements = document.getElementsByClassName("error");
  for (var i = 0; i < errorElements.length; i++) {
    errorElements[i].textContent = "";
  }

  // Validate Full Name
  var u_name = document.getElementById("u_name").value.trim();
  var nameRegex =
    /^(?:(?:Dr|Er|Mr|Mrs|Ms|Rev|PhD|MD)\.\s)?[A-Z][a-zA-Z'’-]{1,}(?:\s[A-Z][a-zA-Z'’-]{1,})+$/;

  if (u_name === "") {
    document.getElementById("error_u_name").textContent =
      "Please enter your full name.";
    isValid = false;
  } else if (!nameRegex.test(u_name)) {
    document.getElementById("error_u_name").textContent =
      "Please enter a valid full name (e.g., Mr. Bipin Chapai).";
    isValid = false;
  } else {
    document.getElementById("error_u_name").textContent = "";
  }

  // Validate Address
  var u_address = document.getElementById("u_address").value.trim();
  if (u_address === "") {
    document.getElementById("error_u_address").textContent =
      "Please enter your address.";
    isValid = false;
  } else if (!/\b[a-zA-Z]{3,}\b/.test(u_address)) {
    document.getElementById("error_u_address").textContent =
      "Please enter a valid address.";
    isValid = false;
  }

  // Validate Email
  var u_email = document.getElementById("u_email").value.trim();
  if (u_email === "") {
    document.getElementById("error_u_email").textContent =
      "Please enter your email address.";
    isValid = false;
  } else if (!/^[a-zA-Z]+[a-zA-Z0-9._-]*@gmail\.com$/.test(u_email)) {
    document.getElementById("error_u_email").textContent =
      "Please enter a valid email address.";
    isValid = false;
  }

  // Validate Phone Number
  var u_phone = document.getElementById("u_phone").value.trim();
  if (u_phone === "") {
    document.getElementById("error_u_phone").textContent =
      "Please enter your phone number.";
    isValid = false;
  } else if (!/^(98|97)/.test(u_phone)) {
    document.getElementById("error_u_phone").textContent =
      "Phone number must start with 98 or 97.";
    isValid = false;
  } else if (!/^[0-9]{10}$/.test(u_phone)) {
    document.getElementById("error_u_phone").textContent =
      "Phone number must be exactly 10 digits long.";
    isValid = false;
  }

  // Validate Gender
  var u_gender = document.getElementById("u_gender").value;
  if (u_gender === "Gender") {
    document.getElementById("error_u_gender").textContent =
      "Please select your gender.";
    isValid = false;
  }

  // Validate Password
  var u_password = document.getElementById("u_password").value.trim();
  if (u_password === "") {
    document.getElementById("error_u_password").textContent =
      "Please enter a password.";
    isValid = false;
  } else if (
    !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/.test(
      u_password
    )
  ) {
    document.getElementById("error_u_password").textContent =
      "Password requires lowercase, uppercase, digit, and special character.";
    isValid = false;
  }

  // Validate Confirm Password
  var u_cpassword = document.getElementById("u_cpassword").value.trim();
  if (u_cpassword === "") {
    document.getElementById("error_u_cpassword").textContent =
      "Please confirm your password.";
    isValid = false;
  } else if (u_cpassword !== u_password) {
    document.getElementById("error_u_cpassword").textContent =
      "Password and confirm password do not match.";
    isValid = false;
  }

  return isValid;
}

// Add event listeners to validate on input change
document.getElementById("u_name").addEventListener("input", function () {
  validateName();
});
document.getElementById("u_address").addEventListener("input", function () {
  validateAddress();
});
document.getElementById("u_email").addEventListener("input", function () {
  validateEmail();
});
document.getElementById("u_phone").addEventListener("input", function () {
  validatePhone();
});
document.getElementById("u_gender").addEventListener("change", function () {
  validateGender();
});
document.getElementById("u_password").addEventListener("input", function () {
  validatePassword();
});
document.getElementById("u_cpassword").addEventListener("input", function () {
  validateConfirmPassword();
});

// Functions to validate each field individually
function validateName() {
  var u_name = document.getElementById("u_name").value.trim();
  var errorElement = document.getElementById("error_u_name");
  errorElement.textContent = "";

  var nameRegex =
    /^(?:(?:Dr|Er|Mr|Mrs|Ms|Rev|PhD|MD)\.\s)?[A-Z][a-zA-Z'’-]{1,}(?:\s[A-Z][a-zA-Z'’-]{1,})+$/;

  if (u_name === "") {
    errorElement.textContent = "Please enter your full name.";
  } else if (!nameRegex.test(u_name)) {
    errorElement.textContent =
      "Please enter a valid full name (e.g., Mr. Bipin Chapai).";
  }
}

function validateAddress() {
  var u_address = document.getElementById("u_address").value.trim();
  var errorElement = document.getElementById("error_u_address");
  errorElement.textContent = "";
  if (u_address === "") {
    errorElement.textContent = "Please enter your address.";
  } else if (!/\b[a-zA-Z]{3,}\b/.test(u_address)) {
    errorElement.textContent = "Please enter a valid address.";
  }
}

function validateEmail() {
  var u_email = document.getElementById("u_email").value.trim();
  var errorElement = document.getElementById("error_u_email");
  errorElement.textContent = "";
  if (u_email === "") {
    errorElement.textContent = "Please enter your email address.";
  } else if (!/^[a-zA-Z]+[a-zA-Z0-9._-]*@gmail\.com$/.test(u_email)) {
    errorElement.textContent = "Please enter a valid email address.";
  }
}

function validatePhone() {
  var u_phone = document.getElementById("u_phone").value.trim();
  var errorElement = document.getElementById("error_u_phone");
  errorElement.textContent = "";

  if (u_phone === "") {
    errorElement.textContent = "Please enter your phone number.";
  } else if (!/^(98|97)/.test(u_phone)) {
    errorElement.textContent = "Phone number must start with 98 or 97.";
  } else if (!/^[0-9]{10}$/.test(u_phone)) {
    errorElement.textContent = "Phone number must be exactly 10 digits long.";
  }
}

function validateGender() {
  var u_gender = document.getElementById("u_gender").value;
  var errorElement = document.getElementById("error_u_gender");
  errorElement.textContent = "";
  if (u_gender === "Gender") {
    errorElement.textContent = "Please select your gender.";
  }
}

function validatePassword() {
  var u_password = document.getElementById("u_password").value.trim();
  var errorElement = document.getElementById("error_u_password");
  errorElement.textContent = "";
  if (u_password === "") {
    errorElement.textContent = "Please enter a password.";
  } else if (
    !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/.test(
      u_password
    )
  ) {
    errorElement.textContent =
      "Password requires lowercase, uppercase,digit, special char.";
  }
}

function validateConfirmPassword() {
  var u_password = document.getElementById("u_password").value.trim();
  var u_cpassword = document.getElementById("u_cpassword").value.trim();
  var errorElement = document.getElementById("error_u_cpassword");
  errorElement.textContent = "";
  if (u_cpassword === "") {
    errorElement.textContent = "Please confirm your password.";
  } else if (u_cpassword !== u_password) {
    errorElement.textContent = "Password and confirm password do not match.";
  }
}
