"use strict";

// This block will run when the DOM is loaded (once elements exist)
window.addEventListener("DOMContentLoaded", () => {

    //function to check if date is in valid format
    //taken from https://bobbyhadz.com/blog/javascript-validate-date-yyyy-mm-dd#:~:text=The%20toISOString()%20method%20returns,as%20YYYY%2DMM%2DDD%20.
    function dateIsValid(dateStr) {
        return /^\d{4}-\d{2}-\d{2}$/.test(dateStr);
    }

    //check is a string is comma seperated
    function isValidString(string){
        return (/^(([a-zA-Z0-9 ](,)?)*)+$/).test(string);
    }

    //check if a string is a url
    function isAUrl(urlString){
        try { 
            return Boolean(new URL(urlString)); 
        }
        catch(e){ 
            return false; 
        }
    }
    //select the add video form
    const voteForm = document.querySelector("#addvid");

    // replace 'event name here' with the correct event
    voteForm.addEventListener("submit", (ev) => {
        // declare a boolean flag named error here
        let error = new Boolean(false);

        //Title
        const Title = document.getElementById("title");
        const TitleError = document.querySelector("#title+span");
        // validate that something was inputted, and handle appropriately
        if (Title.value == 0) {
            error = true;
            TitleError.classList.remove("hidden");
        }
        else {
            TitleError.classList.add("hidden");
        }

        //Rating
        const Rating = document.getElementById("rating");
        const RatingError = document.querySelector("#rating+span");
        // validate that something was inputted, and handle appropriately
        if (Rating.value == 0) {
            error = true;
            RatingError.classList.remove("hidden");
        }
        else {
            RatingError.classList.add("hidden");
        }


        //Check if something was checked from the Genre check boxes
        const Genre = document.getElementsByName("genre[]")
        const GenreError = document.querySelector("fieldset+span");
        let checked = new Boolean(false);
        for (let i = 0; i < Genre.length; i++) {
            if (Genre[i].checked) {
                checked = true;
            }
        }
        // check if a genre checkbox was chosen and handle appropriately
        if (checked == false) {
            error = true;
            GenreError.classList.remove("hidden");
        }
        else {
            GenreError.classList.add("hidden");
        }

        //MPPA 
        const MPPA = document.getElementById("MPAA");
        const MPPAError = document.querySelector("#MPAA+span");
        // validate that something was selected, and handle appropriately
        if (MPPA.value == 0) {
            error = true;
            MPPAError.classList.remove("hidden");
        }
        else {
            MPPAError.classList.add("hidden");
        }

        //Year
        const Year = document.getElementById("year");
        const YearError = document.querySelector("#year+span");
        // validate that something was selected, and handle appropriately
        if (Number.isNaN(Year) || Year.value == 0) {
            error = true;
            YearError.classList.remove("hidden");
        }
        else {
            YearError.classList.add("hidden");
        }

        //Runtime
        const Runtime = document.getElementById("runtime");
        const RuntimeError = document.querySelector("#runtime+span");
        // validate that something was selected, and handle appropriately
        if (Number.isNaN(Runtime) || Runtime.value == 0) {
            error = true;
            RuntimeError.classList.remove("hidden");
        }
        else {
            RuntimeError.classList.add("hidden");
        }

        //Studio
        const Studio = document.getElementById("studio");
        const StudioError = document.querySelector("#studio+span");
        // validate that something was inputted, and handle appropriately
        if (Studio.value == 0) {
            error = true;
            StudioError.classList.remove("hidden");
        }
        else {
            StudioError.classList.add("hidden");
        }

        //Release date
        const Release = document.getElementById("release");
        const ReleaseError = document.querySelector("#release+span");
        const CheckRelease = dateIsValid(Release.value);
        // validate that something was inputted, and handle appropriately
        if (CheckRelease == false) {
            error = true;
            ReleaseError.classList.remove("hidden");
        }
        else {
            ReleaseError.classList.add("hidden");
        }

        //Streaming Release date
        const Streaming = document.getElementById("streaming");
        const StreamingError = document.querySelector("#streaming+span");
        const CheckStreaming = dateIsValid(Streaming.value);
        // validate that something was inputted, and handle appropriately
        if (CheckStreaming == false) {
            error = true;
            StreamingError.classList.remove("hidden");
        }
        else {
            StreamingError.classList.add("hidden");
        }

        //Actors
        const Actors = document.getElementById("actors");
        const ActorsError = document.querySelector("#actors+span");
        const isValidList = isValidString(Actors.value);
        // validate that something was inputted and is comma seperated, and handle appropriately
        if (Actors.value == 0 || isValidList == false) {
            error = true;
            ActorsError.classList.remove("hidden");
        }
        else {
            ActorsError.classList.add("hidden");
        }

        //Studio
        const Summary = document.getElementById("summary");
        const SummaryError = document.querySelector("#summary+span");
        // validate that something was inputted, and handle appropriately
        if (Summary.value == 0) {
            error = true;
            SummaryError.classList.remove("hidden");
        }
        else {
            SummaryError.classList.add("hidden");
        }

        //Check if something was checked from the video type check boxes
        const Videotype = document.getElementsByName("videotype[]")
        const VideotypeError = document.querySelector("#vidtypeid+span");
        let checked2 = new Boolean(false);
        for (let i = 0; i < Videotype.length; i++) {
            if (Videotype[i].checked) {
                checked2 = true;
            }
        }
        // check if a video type checkbox was chosen and handle appropriately
        if (checked2 == false) {
            error = true;
            VideotypeError.classList.remove("hidden");
        }
        else {
            VideotypeError.classList.add("hidden");
        }

        //Img file upload
        const Img = document.getElementById("imgupload");
        const ImgError = document.querySelector("#imgupload+span");
        // validate that something was inputted, and handle appropriately
        if (Img.value == 0) {
            error = true;
            ImgError.classList.remove("hidden");
        }
        else {
            ImgError.classList.add("hidden");
        }

        //Url
        const Url = document.getElementById("coverlink");
        const UrlError = document.querySelector("#coverlink+span");
        const isValidURL = isAUrl(Url.value);
        // validate that something was inputted, and handle appropriately
        if (isValidURL == false) {
            error = true;
            UrlError.classList.remove("hidden");
        }
        else {
            UrlError.classList.add("hidden");
        }

        // Make this conditional on if there were any errors above
        if (error == true) {
            ev.preventDefault();
        }

    }); // event listener for form submit
}); // event listener for dom content loaded