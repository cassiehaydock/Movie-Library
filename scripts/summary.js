"use strict";

window.addEventListener("DOMContentLoaded", () => {

//get the summary input box
const textarea = document.getElementById("summary");
//get the span that will display the word count
const count = document.getElementById("count");
//define the mac character count
const max = 2500;

//everytime there is input
textarea.addEventListener("input", wordCounter);

function wordCounter(){
    //let the count_of_characters equal the number of charcters in the textarea
    let count_of_characters = textarea.value.length;
    //let the counter be the max num of characters - the number of character in the textarea
    let counter = max - count_of_characters;
    //display the number of characters left
    count.textContent = counter;
}

});