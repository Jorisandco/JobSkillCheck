import $ from 'jquery';
import {APICals} from "./API.js";

export class Poll {
     LinkExtension = "/poll";
     PollID;

    constructor(pollID) {
        this.PollID = pollID;
    }

     async GetPollResults(){
        const api = new APICals();
        try {
            const response = await api.post(this.LinkExtension + '/getPollResults', {
                pollID: this.PollID
            });
            return response.results;
        } catch (error) {
            console.error("Error fetching poll results:", error);
            return null;
        }
    }

     async HasUserAnswered(userID){
        const api = new APICals();
        try {
            const response = await api.post(this.LinkExtension + '/hasUserAnswered', {
                pollID: this.PollID,
                userID: userID
            });
            return response.hasAnswered;
        } catch (error) {
            console.error("Error checking if user has answered:", error);
            return false;
        }
    }

     async CreatePoll(question, answers){
        const api = new APICals();
        try {
            const response = await api.post(this.LinkExtension + '/createPoll', {
                question: question,
                answers: answers
            });

            return response.success;
        } catch (error) {
            console.error("Error creating poll:", error);
            return false;
        }
    }
}