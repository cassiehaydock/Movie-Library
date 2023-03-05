"use strict";

window.addEventListener("DOMContentLoaded", () => {
    //get the registration form
  const voteForm = document.querySelector("#deletevid");

  voteForm.addEventListener("submit", (ev) => {

    let confirmed = confirm('Are you super duper sure?');
    if(confirmed == false) {
        ev.preventDefault();
    }
  });
});