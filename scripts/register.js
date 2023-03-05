"use strict";

// This block will run when the DOM is loaded (once elements exist)
window.addEventListener("DOMContentLoaded", () => {

  // Uses a regular expression that validates email, and returns true or false if the email is
  // valid.
  function emailIsValid(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }
  //get the registration form
  const voteForm = document.querySelector("#registration");

  // replace 'event name here' with the correct event
  voteForm.addEventListener("submit", (ev) => {
    // declare a boolean flag named error here
    let error = new Boolean(false);
    
    //first name
    const FirstName = document.getElementById("firstname");
    const FirstNameError = document.querySelector("#firstname+span");
    // validate that something was inputted, and handle appropriately
    if (FirstName.value == 0) {
      error = true;
      FirstNameError.classList.remove("hidden");
    }
    else {
      FirstNameError.classList.add("hidden");
    }

    //last name
    const LastName = document.getElementById("lastname");
    const LastNameError = document.querySelector("#lastname+span");
    // validate that something was inputted, and handle appropriately
    if (LastName.value == 0) {
      error = true;
      LastNameError.classList.remove("hidden");
    }
    else {
      LastNameError.classList.add("hidden");
    }

    //username
    const Username = document.getElementById("username");
    const UsernameError = document.querySelector("#username+span");
    // validate that something was inputted, and handle appropriately
    if (Username.value == 0) {
      error = true;
      UsernameError.classList.remove("hidden");
    }
    else {
      UsernameError.classList.add("hidden");
    }

    //get the first emal input
    const emailInput = document.getElementById("email1");
    //get its span error message
    const emailError = document.querySelector("#email1+span");

    // check email if valid (using the function provided above) and handle appropriately
    const isvalidEmail = emailIsValid(emailInput.value);
    if (isvalidEmail == false) {
      error = true;
      emailError.classList.remove("hidden");
    }
    else {
      emailError.classList.add("hidden");
    }

    //get second email input
    const emailInput2 = document.getElementById("email2");
    //get its span error message
    const emailError2 = document.querySelector("#email2+span");

    // check email if valid (using the function provided above) and handle appropriately
    const isvalidEmail2 = emailIsValid(emailInput2.value);
    if (isvalidEmail2 == false || emailInput.value != emailInput2.value) {
      error = true;
      emailError2.classList.remove("hidden");
    }
    else {
      emailError2.classList.add("hidden");
    }

    //password
    const Password = document.getElementById("newpass");
    const PasswordError = document.querySelector("#newpass+span");
    // validate that something was inputted, and handle appropriately
    if (Password.value == 0 || Password.value.length < 8) {
      error = true;
      PasswordError.classList.remove("hidden");
    }
    else {
      Password.classList.add("hidden");
    }

    // Make this conditional on if there were any errors above
    if (error == true) {
      ev.preventDefault();
    }
  }); // event listener for form submit
}); // event listener for dom content loaded
