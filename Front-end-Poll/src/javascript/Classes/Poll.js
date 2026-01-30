import $ from 'jquery';
import {APICals} from "./API.js";
import {Cookie} from "./Cookie.js";

export class Poll {
    LinkExtension = "/poll";
    PollID;

    constructor(pollID) {
        this.PollID = pollID;
    }

    async GetPollResults() {
        const api = new APICals();
        try {
            return await api.post(this.LinkExtension + '/get-poll-answers', {
                POLL_ID: this.PollID
            });

        } catch (error) {
            console.error("Error fetching poll results:", error);
            return null;
        }
    }

    async GetPoll() {
        const api = new APICals();
        try {
            return await api.post(this.LinkExtension + '/get-poll-data', {
                POLL_ID: this.PollID
            });

        } catch (error) {
            console.error("Error fetching poll data:", error);
            return null;
        }
    }

    async HasUserAnswered(sessionToken) {
        const api = new APICals();
        try {
            return await api.post(this.LinkExtension + '/user-has-answered', {
                POLL_ID: this.PollID,
                Session: sessionToken
            });
        } catch (error) {
            console.error("Error checking if user has answered:", error);
            return false;
        }
    }

    async CreatePoll(question, answers, exp) {
        const api = new APICals();
        try {
            const response = await api.post(this.LinkExtension + '/submit-poll', {
                QUESTION: question,
                EXPIRES: exp,
                ANSWERS: answers,
                Session: Cookie.getCookie("Session")
            });

            return response;
        } catch (error) {
            console.error("Error creating poll:", error);
            return false;
        }
    }

    AddPollToFrontend(answers, MainQuestion, expiry, loggedin = true) {

        let formPoll = `
        <div class="poll-expiry">Poll expires on: ${expiry}</div>
        <h3>${MainQuestion}</h3>
        <form id="poll-form">
    `;

        answers.forEach(answer => {
            formPoll += `
            <div class="poll-answer">
                <input 
                    type="radio"
                    id="answer-${answer.idPoll_answers}"
                    name="poll-answer"   
                    value="${answer.idPoll_answers}"
                >
                <label 
                    for="answer-${answer.idPoll_answers}"
                >
                    ${answer.Answer}
                </label>
            </div>
        `;
        });

        formPoll += `</form> ${loggedin ? '<button id="submit-poll-answer">Submit Answer</button>' : ""}`;

        $("#poll").html(formPoll);

    }

    RevealAnswers(answers, question, expired = false, ) {
        const totalVotes = answers.reduce(
            (sum, a) => sum + a[0].total_answers,
            0
        ) || 1;

        let html = `<div class="poll-results"> <h1>${question}</h1>`;

        answers.forEach((a) => {
            const votes = a[0].total_answers;
            const text = a[1];
            const color = a[2];
            const percent = Math.round((votes / totalVotes) * 100);

            html += `
            <div class="poll-result">
                <div class="poll-label">
                    <span>${text}</span>
                    <span>${percent}%</span>
                </div>
                <div class="poll-bar-bg">
                    <div 
                        class="poll-bar-fill"
                        data-percent="${percent}"
                        style="background:${color}; width:0">
                    </div>
                </div>
            </div>
        `;
        });

        html += `</div> ${expired? "" : '<button id="retake-poll">Retake Poll</button>'}`;

        $("#poll").append(html);

        setTimeout(() => {
            $(".poll-bar-fill").each(function () {
                const percent = $(this).data("percent");
                $(this).css("width", percent + "%");
            });
        }, 50);
    }
}