import {APICals} from "./API.js";
import {Cookie} from "./Cookie.js";
import $ from "jquery"

export class Users {
    sessionToken = Cookie.getCookie("Session")

    async IsUserLoggedIn() {
        const api = new APICals();
        try {
            const response = await api.post("/user/is-logged-in", {
                Session: this.sessionToken
            });

            if (response.status === "error") {
                return false;
            }

            return response.data.isLoggedIn;
        } catch (error) {
            console.error("Error checking user login status:", error);
            return false;
        }
    }

    async login(email, stayLogged) {
        const api = new APICals();
        try {
            const response = await api.post("/user/login", {
                EMAIL: email,
                stayLoggedIn: stayLogged
            });

            if (response.success === false) {
                return false;
            }

            Cookie.setCookie("Session", response.data.SESSION_ID, 1);

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