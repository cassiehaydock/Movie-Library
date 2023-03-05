"use strict";
window.addEventListener("DOMContentLoaded", () => {

    //get div to put modal details into
    const modal = document.getElementById("mods");

    //get all the details links
    const detailsLinks = document.querySelectorAll(".detailslink");

    //add an eventlistner to all details links for each movie
    for (const link of detailsLinks) {
        link.addEventListener("click", showModal);
    }

    function showModal(ev){
        ev.preventDefault();
        //define a new xml http request
        const xhr = new XMLHttpRequest();

        //open new xml http request
        xhr.open("get", this.href);

        //when everything is done loading
        xhr.addEventListener("load",  function(){
            //if there are no errors
            if(xhr.status==200){
            //display the results in the div
            modal.innerHTML=xhr.response;
            //remove hidden attribute 
            modal.classList.remove("modal");

            //get the close icon
            const close = document.getElementById("close");

            //when its clicked
            close.addEventListener("click", closeModal);
        
            //close the modal
            function closeModal(){
                const win = document.getElementById("mods");
                win.classList.add("modal");
            }
        }
        });

        //if theres an error display and error message
        xhr.addEventListener("error", function(){
            modal.innerHTML="<h3>Could not load the movies details</h3>"
        });

        //send
        xhr.send();
    }


});