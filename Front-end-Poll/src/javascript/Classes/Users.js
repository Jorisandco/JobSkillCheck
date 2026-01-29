import {APICals} from "./API.js";
import {Cookie} from "./Cookie.js";

export class Users {
    sessionToken;

    constructor(sessionToken) {
        this.sessionToken = sessionToken;
    }

    async IsUserLoggedIn() {
        const api = new APICals();
        try {
            const response = await api.post("/user/is-logged-in", {
                Session: this.sessionToken
            });

            if (response.status === "error") {
                return false;
            }

            return response.isLoggedIn;
        } catch (error) {
            console.error("Error checking user login status:", error);
            return false;
        }
    }

    async login() {
        const api = new APICals();
        try {
            const response = await api.post("/user/login", {
                Session: this.sessionToken
            });

            if (response.success === false) {
                return false;
            }

            Cookie.setCookie("Session", response.newSessionToken, 1);

            return response.userData;
        } catch (error) {
            console.error("Error during user login:", error);
            return null;
        }
    }

    async answerQuestion(answerId) {
        const api = new APICals();
        try {
            const response = await api.post("/user/answer-question", {
                Session: this.sessionToken,
                answerID: answerId,
            });

            return response.success;
        } catch (error) {
            console.error("Error submitting answer to question:", error);
            return false;
        }
    }
}