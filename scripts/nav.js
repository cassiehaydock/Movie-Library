"use strict";

// This block will run when the DOM is loaded (once elements exist)
window.addEventListener("DOMContentLoaded", () => {

//get the dropdown chevron button
const chevronHide = document.getElementById("dropdown");
//get the dropup chevron button
const chevronShow = document.getElementById("dropup");

//event listners to listn for the click of the chevrons
chevronHide.addEventListener("click", hideNav);
chevronShow.addEventListener("click", showNav)

//function to hide the nav
function hideNav(){
     //get the div which contains the nav info
    const click = document.getElementsByClassName("toHide"); 
    //foreach element iof the div hide them 
    for (const clk of click) {
         clk.style.visibility = 'hidden';
    }
    //hide the dropdown chevron
    chevronHide.style.display = 'none';
    //show the dropup chevron
    chevronShow.style.display = 'block';
}

//funtion to show the nav
function showNav(){
    //get the div which contains the nav info
   const click = document.getElementsByClassName("toHide");  
   //foreach element of the div make them visible
   for (const clk of click) {
        clk.style.visibility = 'visible';
   }
   //hide the dropup chevron
   chevronShow.style.display = 'none';
   //show the dropdown chevron
   chevronHide.style.display = 'block';
}

});