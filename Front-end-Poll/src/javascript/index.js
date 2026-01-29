import {Users} from "./Classes/Users.js";
import {Poll} from "./Classes/Poll.js";
import $ from "jquery";

const user = new Users(); // like jQuery plugin

const loggedIn = await user.IsUserLoggedIn()

if (loggedIn === false) {
    $(".email-form").css("display", "flex");
}

$("#login").on("click", async function () {
    const userData = await user.login(
        $("#email").val().toString(),
        true
    );

    if (userData.status === "success") {
        alert("Login successful! Welcome, " + userData.name);
        location.reload();
    } else {
        alert("Login failed. Please try again.");
    }
});