"use strict";

window.addEventListener("DOMContentLoaded", () => {

    //get the password input 
    const password = document.getElementById("newpass");
    //get the div/span to insert the visual into
    const pwdStrength = document.getElementById("passStrength");

    //listen for input into the password field
    password.addEventListener("input", strengthCalc);

    //function to calcukate strength
    function strengthCalc(){
        //regular expressions taken from https://martech.zone/javascript-password-strength/
        //regular expression for strong password (14 characters or more and has a combination of symbols, caps, text) 
        const strong = new RegExp("^(?=.{14,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        //regular expression for a medium password (10 characters or more and has a combination of symbols, caps, text)
        const medium = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        //length is under 8 characters 
        const minimum = new RegExp("(?=.{8,}).*", "g");

        //if password value is not over 8 characters
        if(minimum.test(password.value) == false)
        {
            pwdStrength.textContent = "Must be a Minimum of 8 Characters";
            pwdStrength.style.color = "red";
        }
        //if password value meets the strong regular expression requirements
        else if(strong.test(password.value) == true)
        {
            pwdStrength.textContent = "Strong";
            pwdStrength.style.color = "green";
        }
        //if password value meets the medium regular expression requirements
        else if(medium.test(password.value) == true)
        {
            pwdStrength.textContent = "Medium";
            pwdStrength.style.color = "red";
        }
        //else its weak
        else{
            pwdStrength.textContent = "Weak";
            pwdStrength.style.color = "red";
        }
    }
    

});