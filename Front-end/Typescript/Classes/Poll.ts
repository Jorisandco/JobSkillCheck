import $ from 'jquery';
import {APICals} from "./API";

export class Poll {
    private LinkExtension: string = "/polls";
    private PollID: number;

    constructor(pollID: number) {
        this.PollID = pollID;
    }

    public async SubmitAnswer(answerID: number): Promise<boolean> {
        const api = new APICals();
        try {
            const response = await api.post(this.LinkExtension + 'submitPollAnswer', {
                pollID: this.PollID,
                answerID: answerID
            });
            return response.success;
        } catch (error) {
            console.error("Error submitting poll answer:", error);
            return false;
        }
    }

    public async GetPollResults(): Promise<any> {
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

    public async HasUserAnswered(userID: number): Promise<boolean> {
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

    public async CreatePoll(question: string, answers: string[]): Promise<boolean> {
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