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


// Remove answer block
$(document).on("click", ".remove-answer", function () {
    $(this).closest(".selection").remove();
});

// Add a new answer block dynamically
$(document).ready(() => {
    $("#add-answer").click(() => {
        const newBlock = `
            <div class="selection">
                <input type="text" class="answer-text" placeholder="Enter answer option"/>
                <input type="color" class="answer-color" value="#ff0000"/>
                <button class="remove-answer">Remove</button>
            </div>
        `;
        $(".answers").append(newBlock);
    });

    $(document).on("click", ".remove-answer", function () {
        $(this).closest(".selection").remove();
    });

    $("#get-values").click(() => {
        const answers = [];
        $(".selection").each(function () {
            const text = $(this).find(".answer-text").val();
            const color = $(this).find(".answer-color").val();
            answers.push({text, color});
        });
        console.log(answers);
    });

    $("#create-poll").click(async () => {
        const expire = $("#poll-expiry").val().toString();
        const question = $("#poll-question").val().toString();
        const answers = [];
        $(".selection").each(function () {
            const text = $(this).find(".answer-text").val().toString();
            const color = $(this).find(".answer-color").val().toString();
            answers.push({answer: text, barcolour: color});
        });

        const poll = new Poll();
        const success = await poll.CreatePoll(question, answers, expire);

        if (success.status === "success") {
            alert("Poll created successfully!");
            window.location.href = "/index.html?pollID=" + success.data.poll_id;
        } else {
            alert("Failed to create poll. Please try again.");
        }
    })

    // Set minimum date-time for poll expiry to current date-time
    const future = new Date();
    future.setHours(future.getHours() + 1);

    const year = future.getFullYear();
    const month = String(future.getMonth() + 1).padStart(2, "0");
    const day = String(future.getDate()).padStart(2, "0");
    const hours = String(future.getHours()).padStart(2, "0");
    const minutes = String(future.getMinutes()).padStart(2, "0");

    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    $("#poll-expiry").attr("min", minDateTime);
    $("#poll-expiry").val(minDateTime);
});