import {Users} from "./Classes/Users.js";
import {Poll} from "./Classes/Poll.js";
import $ from "jquery";

const user = new Users();
const urlParams = new URLSearchParams(window.location.search);
const pollID = urlParams.get("pollID");
const loggedIn = user.IsUserLoggedIn()

if (loggedIn === false) {
    $(".email-form").css("display", "flex");
}

$("#login").on("click", async function () {
    const userData = await user.login($("#email").val().toString(), true);
    if (userData.status === "success") {
        console.log("hi")
        alert("Login successful! Welcome");
        $(".email-form").css("display", "none");
        window.location.reload();
    } else {
        alert("Login failed. Please try again.");
    }
});


if (pollID === null) {
    $(document).on("click", ".remove-answer", function () {
        $(this).closest(".selection").remove();
    });

    $(document).ready(() => {
        $("#add-answer").click(() => {
            const newBlock = `
            <div class="selection">
                <input maxlength="128"  type="text" class="answer-text" placeholder="Enter answer option"/>
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
}


if (pollID) {
    $(document).ready(async () => {
        const poll = new Poll(pollID);
        const results = await poll.GetPoll();

        if (await user.IsUserLoggedIn() === false) {
            $(".email-form").css("display", "flex");
            poll.AddPollToFrontend(results.data.answers, results.data.Question, results.data.Expires, false);
            $("#Create-pollForm").css("display", "none");
            return;
        }

        const hasUserAnswered = await poll.HasUserAnswered(user.sessionToken);

        function expired() {
            return new Date(results.data.Expires) < new Date();
        }

        if (results.status === "success") {
            if (!hasUserAnswered.data.has_answered && !expired()) {
                poll.AddPollToFrontend(results.data.answers, results.data.Question, results.data.Expires);
                $("#Create-pollForm").css("display", "none");
            } else {
                const pollResults = await poll.GetPollResults();

                if (pollResults.status === "success") {
                    poll.RevealAnswers(pollResults.data, results.data.Question, expired());
                } else {
                    console.error("Failed to fetch poll results.");
                }
            }
        } else {
            console.error("Failed to fetch poll results.");
        }

        $(document).on("click", "#submit-poll-answer", async () => {

            if (user.sessionToken === null)
                return alert("You must be logged in to submit an answer.");

            const selectedAnswer = $("input[name='poll-answer']:checked").val();
            if (!selectedAnswer) {
                alert("Please select an answer before submitting.");
                return;
            }

            const answerSubmitted = await user.answerQuestion(selectedAnswer);

            if (answerSubmitted.status === "success") {
                const results = await poll.GetPollResults();
                $("#poll").empty();
                poll.RevealAnswers(results.data, results.data.Question);
            } else {
                alert("Failed to submit your answer. Please try again.");
            }
        });

        $(document).on("click", "#retake-poll", () => {
            $("#poll").empty();
            poll.AddPollToFrontend(
                results.data.answers,
                results.data.Question,
                results.data.Expires
            );
        });

        $("#Create-pollForm").css("display", "none");
    });
}